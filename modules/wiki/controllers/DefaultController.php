<?php

namespace app\modules\wiki\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\FileHelper;
use app\modules\wiki\models\WikiPagesCache;
use app\modules\wiki\models\WikiLiveSessions;
use app\models\User;

class DefaultController extends Controller
{
    /**
     * Interceptador inteligente para desativar layouts em requisições assíncronas HTMX.
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->request->headers->has('HX-Request')) {
                $this->layout = false;
            } else {
                $this->layout = '@app/views/layouts/main';
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['access-wiki'], // Exige a permissão RBAC access-wiki
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module.
     * Handles HTMX/Ajax partial rendering to keep SPA tabs clean.
     */
    public function actionIndex()
    {
        // Força sincronização inicial das páginas markdown locais se o cache estiver vazio
        $count = WikiPagesCache::find()->count();
        if ($count == 0) {
            $this->runSyncLocal();
        }

        // Busca todas as páginas de cache para renderizar a árvore lateral
        $pages = WikiPagesCache::find()->select(['path', 'title'])->asArray()->all();

        // Monta estrutura de árvore de diretórios
        $tree = $this->buildDirectoryTree($pages);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', [
                'tree' => $tree,
                'rawPages' => $pages,
            ]);
        }

        return $this->render('index', [
            'tree' => $tree,
            'rawPages' => $pages,
        ]);
    }

    /**
     * Exibe o conteúdo de uma página específica em formato JSON ou HTML parcial.
     */
    public function actionView($path)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $page = WikiPagesCache::findOne(['path' => $path]);
        if (!$page) {
            throw new NotFoundHttpException('Página não encontrada no cache.');
        }

        // Limpa sessões expiradas antes de retornar
        $this->cleanExpiredSessions();

        return [
            'path' => $page->path,
            'title' => $page->title,
            'content' => $page->content,
            'created_by' => $page->created_by ?: 'Sistema',
            'admin_id' => $page->admin_id,
            'admin_name' => $page->adminUser ? $page->adminUser->username : 'Nenhum',
            'last_synced_at' => date('d/m/Y H:i:s', $page->last_synced_at),
        ];
    }

    /**
     * Salva o conteúdo do Markdown de uma página (e cria se não existir) no cache e física.
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $path = $request->post('filepath') ?: $request->get('filepath');
        $content = $request->post('markdown_content') ?: $request->get('markdown_content');
        $adminId = $request->post('admin_id');

        if (empty($path)) {
            return ['success' => false, 'message' => 'Caminho do arquivo não fornecido.'];
        }

        // Sanitização de Path Traversal
        $path = str_replace(['..', '\\'], ['', '/'], $path);
        $path = ltrim($path, '/');

        if (!str_ends_with(strtolower($path), '.md')) {
            $path .= '.md';
        }

        $isNew = false;
        $page = WikiPagesCache::findOne(['path' => $path]);
        if (!$page) {
            $isNew = true;
            $page = new WikiPagesCache();
            $page->path = $path;
            $page->created_by = Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Sistema';
        }

        // Se a página já existe, verifica se outra pessoa tem o lock de EDITING ativo
        if (!$isNew) {
            $this->cleanExpiredSessions();
            $activeEditor = WikiLiveSessions::find()
                ->where(['path' => $path, 'status' => 'EDITING'])
                ->andWhere(['!=', 'user_id', Yii::$app->user->id])
                ->one();

            if ($activeEditor) {
                return [
                    'success' => false,
                    'message' => 'Operação negada. O usuário ' . $activeEditor->user->username . ' possui o lock de edição deste arquivo.'
                ];
            }
        }

        // Atualiza os metadados e conteúdo
        $page->content = $content;
        $page->last_synced_at = time();
        $page->sha = md5($content);
        
        if (!empty($adminId)) {
            $page->admin_id = (int)$adminId;
        }

        // Tenta extrair o novo título da página caso o H1 tenha sido editado ou fornecido
        $title = $path;
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            $title = trim($matches[1]);
        }
        $page->title = $title;

        if ($page->save()) {
            // Sincroniza de volta no arquivo físico local (GitOps Fallback)
            $fullPath = Yii::getAlias('@app/docs/') . $path;
            try {
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    FileHelper::createDirectory($dir, 0777);
                }
                file_put_contents($fullPath, $content);
            } catch (\Exception $e) {
                // Silencia se for erro de escrita de disco e retorna sucesso no banco
            }

            return [
                'success' => true,
                'message' => $isNew ? 'Página criada e sincronizada com sucesso!' : 'Página salva e sincronizada com sucesso!',
                'title' => $page->title,
                'path' => $page->path
            ];
        }

        return ['success' => false, 'message' => 'Falha ao salvar a página no banco de dados.'];
    }

    /**
     * Fornece a lista de usuários ativos para seleção de administradores de página.
     */
    public function actionUsersList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return User::find()->select(['id', 'username'])->asArray()->all();
    }

    /**
     * Atualiza o Heartbeat da sessão do usuário e verifica o estado do Lock.
     * Retorna HTML parcial projetado para ser injetado via HTMX Polling.
     */
    public function actionLockStatus($path, $status = 'VIEWING')
    {
        $userId = Yii::$app->user->id;
        $time = time();

        // 1. Limpar sessões inativas/expiradas
        $this->cleanExpiredSessions();

        // 2. Registrar/Atualizar batimento do usuário atual para a página
        // Geramos um ID determinístico baseado no usuário e no caminho para evitar duplicações
        $sessionId = md5($userId . '_' . $path);
        
        $session = WikiLiveSessions::findOne($sessionId);
        if (!$session) {
            $session = new WikiLiveSessions();
            $session->id = $sessionId;
            $session->user_id = $userId;
            $session->path = $path;
        }
        $session->status = $status;
        $session->updated_at = $time;
        $session->save();

        // 3. Checar se outro usuário está editando a mesma página no momento
        $activeEditor = WikiLiveSessions::find()
            ->where(['path' => $path, 'status' => 'EDITING'])
            ->andWhere(['!=', 'user_id', $userId])
            ->one();

        // 4. Retornar resposta formatada
        if ($activeEditor) {
            return '<div class="text-xs text-rose-400 flex items-center gap-1.5 mt-1 font-mono bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 rounded-lg w-fit animate-pulse">'
                 . '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>'
                 . '🔒 <b>' . htmlspecialchars($activeEditor->user->username) . '</b> está editando este arquivo. Suas escritas estão bloqueadas.'
                 . '</div>';
        }

        // Se estiver livre e tiver outro visualizador
        $viewers = WikiLiveSessions::find()
            ->where(['path' => $path, 'status' => 'VIEWING'])
            ->andWhere(['!=', 'user_id', $userId])
            ->all();

        if (!empty($viewers)) {
            $names = [];
            foreach ($viewers as $v) {
                $names[] = $v->user->username;
            }
            return '<div class="text-xs text-emerald-400 flex items-center gap-1.5 mt-1 font-mono bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-lg w-fit">'
                 . '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>'
                 . '👁️ ' . implode(', ', $names) . ' visualizando.'
                 . '</div>';
        }

        // Apenas o próprio usuário ativo
        return '<div class="text-xs text-slate-500 flex items-center gap-1.5 mt-1 font-mono bg-slate-900/50 border border-slate-800/80 px-2 py-0.5 rounded-lg w-fit">'
             . '<span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>'
             . '✓ Livre para edição.'
             . '</div>';
    }

    /**
     * Força a ressincronização em lote dos arquivos físicos da pasta docs/ para o banco.
     */
    public function actionSync()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $successCount = $this->runSyncLocal();
        return [
            'success' => true,
            'message' => "Sincronização concluída! {$successCount} páginas recarregadas com sucesso no cache."
        ];
    }

    // ==========================================
    // MÉTODOS PRIVADOS / AUXILIARES
    // ==========================================

    /**
     * Realiza o scan recursivo na pasta /docs e popula a tabela de cache.
     */
    private function runSyncLocal()
    {
        $docsDir = Yii::getAlias('@app/docs');
        if (!is_dir($docsDir)) {
            return 0;
        }

        $files = FileHelper::findFiles($docsDir, [
            'only' => ['*.md'],
            'recursive' => true,
        ]);

        $count = 0;
        $time = time();

        foreach ($files as $file) {
            $relativePath = str_replace($docsDir . DIRECTORY_SEPARATOR, '', $file);
            $content = file_get_contents($file);

            // Extrai H1 como título
            $title = $relativePath;
            if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
                $title = trim($matches[1]);
            }

            // Normaliza caminhos de barras invertidas para barras normais (compatibilidade Windows/Linux)
            $relativePath = str_replace('\\', '/', $relativePath);

            $page = WikiPagesCache::findOne($relativePath);
            if (!$page) {
                $page = new WikiPagesCache();
                $page->path = $relativePath;
            }
            $page->sha = md5($content); // SHA local baseado em Hash
            $page->title = $title;
            $page->content = $content;
            $page->last_synced_at = $time;

            if ($page->save()) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Remove batimentos de sessões maiores que 15 segundos (locks inativos).
     */
    private function cleanExpiredSessions()
    {
        $expirationTime = time() - 15;
        WikiLiveSessions::deleteAll('updated_at < :expirationTime', [':expirationTime' => $expirationTime]);
    }

    /**
     * Agrupa a listagem linear de caminhos em uma árvore aninhada de pastas.
     */
    private function buildDirectoryTree(array $pages)
    {
        $tree = [];

        foreach ($pages as $page) {
            $parts = explode('/', $page['path']);
            $current = &$tree;

            for ($i = 0; $i < count($parts); $i++) {
                $part = $parts[$i];
                if ($i === count($parts) - 1) {
                    // É um arquivo
                    $current[] = [
                        'type' => 'file',
                        'name' => $part,
                        'path' => $page['path'],
                        'title' => $page['title']
                    ];
                } else {
                    // É um diretório
                    if (!isset($current[$part])) {
                        $current[$part] = [
                            'type' => 'dir',
                            'name' => $part,
                            'children' => []
                        ];
                    }
                    $current = &$current[$part]['children'];
                }
            }
        }

        return $tree;
    }
}
