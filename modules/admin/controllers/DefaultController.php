<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\helpers\Html;
use app\models\User;

class DefaultController extends Controller
{
    /**
     * Interceptor inteligente de layout e segurança por RBAC
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Exige login
            if (Yii::$app->user->isGuest) {
                return $this->redirect(['/site/login'])->send();
            }

            // Exige a permissão admin-access
            if (!Yii::$app->user->can('admin-access')) {
                throw new ForbiddenHttpException('Você não tem permissão para acessar esta área.');
            }

            // Desativa o layout para HTMX
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
     * Tela Principal Administrativa (SPA Tabs)
     */
    public function actionIndex()
    {
        $db = Yii::$app->db;

        // 1. Carregar Usuários e suas respectivas permissões
        $users = User::find()->orderBy(['username' => SORT_ASC])->all();
        $usersData = [];
        foreach ($users as $u) {
            // Buscar permissões associadas a cada usuário
            $assignedPerms = $db->createCommand("
                SELECT item_name FROM auth_assignment WHERE user_id = :userId
            ", [':userId' => (string)$u->id])->queryColumn();

            $usersData[] = [
                'id' => $u->id,
                'username' => $u->username,
                'status' => $u->status,
                'created_at' => date('d/m/Y H:i', $u->created_at),
                'permissions' => $assignedPerms
            ];
        }

        // 2. Buscar todas as permissões cadastradas no RBAC
        $allPermissions = $db->createCommand("
            SELECT name, description FROM auth_item WHERE type = 2 ORDER BY name ASC
        ")->queryAll();

        // 3. Garantir inserção de configurações padrões adicionais do Dashboard (Auto-healing / Deploy seguro)
        $defaultExtraSettings = [
            'daily_quote_title' => 'Entusiasmo',
            'daily_quote_text' => 'O entusiasmo é a maior força da alma.',
            'ecosystem_cards_json' => json_encode([
                [
                    'nome' => 'CromIA Gateway',
                    'tag' => 'Inteligência',
                    'tag_style' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                    'bg_style' => 'bg-gradient-to-br from-purple-950/40 to-slate-900 border-purple-900/60 hover:border-purple-500/40',
                    'icone' => '🤖',
                    'descricao' => 'Acesso unificado via chaves privadas a modelos avançados (Deepseek V4, Gemma 4, GLM) sem vazamento de escopo corporativo.',
                    'btn_texto' => 'Acessar Token Privado',
                    'btn_style' => 'bg-purple-600 hover:bg-purple-500 text-white',
                    'disabled' => false,
                    'tab' => 'beneficios'
                ],
                [
                    'nome' => 'P2P Secure Share',
                    'tag' => 'Privacidade',
                    'tag_style' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                    'bg_style' => 'bg-gradient-to-br from-emerald-950/40 to-slate-900 border-emerald-900/60 hover:border-emerald-500/40',
                    'icone' => '🔒',
                    'descricao' => 'Transferência direta de arquivos de dispositivo para dispositivo via WebRTC, rodando sem backend centralizado.',
                    'btn_texto' => 'Abrir P2PFile',
                    'btn_style' => 'bg-emerald-600 hover:bg-emerald-500 text-white',
                    'disabled' => false,
                    'tab' => 'projetos'
                ],
                [
                    'nome' => 'Ferramentas',
                    'tag' => 'Infraestrutura',
                    'tag_style' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                    'bg_style' => 'bg-gradient-to-br from-indigo-950/40 to-slate-900 border-indigo-900/60 hover:border-indigo-500/40',
                    'icone' => '🗜️',
                    'descricao' => 'Coleção de ferramentas web essenciais, gratuitas e 100% privadas. Inclui conversores, geradores e utilitários para desenvolvedores e criadores.',
                    'btn_texto' => 'Acessar Ferramentas',
                    'btn_style' => 'bg-indigo-600 hover:bg-indigo-500 text-white',
                    'disabled' => false,
                    'link' => 'https://crom.run/ferramentas'
                ],
                [
                    'nome' => 'Cromva',
                    'tag' => 'IA',
                    'tag_style' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                    'bg_style' => 'bg-gradient-to-br from-indigo-950/40 to-slate-900 border-indigo-900/60 hover:border-indigo-500/40',
                    'icone' => '🤖',
                    'descricao' => 'Focado na privacidade, o Cromva é uma plataforma voltada para o consumo e organization de conteúdo em markdown. Com uma interface intuitiva, serve como um hub central para anotações.',
                    'btn_texto' => 'Acessar Cromva',
                    'btn_style' => 'bg-indigo-600 hover:bg-indigo-500 text-white',
                    'disabled' => false,
                    'link' => 'https://cromva.crom.run/'
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            'alignment_cards_json' => json_encode([
                [
                    'titulo' => 'Contrato de Cuidado & Vesting',
                    'descricao' => 'Seu histórico e impacto de contribuição técnico permanecem registrados em blockchain interna/wiki. Distribuição de proventos comerciais B2B prioritária para Pilares.',
                    'btn_texto' => 'Ver benefícios',
                    'tab' => 'beneficios'
                ],
                [
                    'titulo' => 'Manifesto do Ecossistema Local-First',
                    'tag' => 'Filosofia',
                    'descricao' => 'Leia as diretrizes completas sobre Soberania Digital, infraestruturas resilientes offline e o porquê de rejeitarmos modelos centralizados.',
                    'btn_texto' => 'Acessar Manifesto Externo',
                    'link' => 'https://crom.me/manifesto'
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        ];

        foreach ($defaultExtraSettings as $key => $val) {
            $exists = $db->createCommand("SELECT COUNT(*) FROM core_settings WHERE key = :key", [':key' => $key])->queryScalar();
            if (!$exists) {
                $db->createCommand()->insert('core_settings', [
                    'key' => $key,
                    'value' => $val
                ])->execute();
            }
        }

        // 3. Carregar Configurações Globais do Portal
        $settings = $db->createCommand("SELECT * FROM core_settings")->queryAll();
        $settingsMap = [];
        foreach ($settings as $s) {
            $settingsMap[$s['key']] = $s['value'];
        }

        // 4. Carregar Módulos do Sistema
        $modules = $db->createCommand("SELECT * FROM core_modules ORDER BY sort_order ASC")->queryAll();

        return $this->render('index', [
            'users' => $usersData,
            'allPermissions' => $allPermissions,
            'settings' => $settingsMap,
            'modules' => $modules
        ]);
    }

    /**
     * Salva ou cria um usuário no sistema
     */
    public function actionSaveUser()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isPost) {
            $id = $request->post('id');
            $username = trim((string)$request->post('username'));
            $password = trim((string)$request->post('password'));

            if (empty($username)) {
                return ['success' => false, 'message' => 'Nome de usuário não pode ser vazio.'];
            }

            if (empty($id)) {
                // Novo Usuário
                if (empty($password)) {
                    return ['success' => false, 'message' => 'A senha é obrigatória para novos usuários.'];
                }

                $exists = User::findOne(['username' => $username]);
                if ($exists) {
                    return ['success' => false, 'message' => 'Este nome de usuário já está em uso.'];
                }

                $user = new User();
                $user->username = $username;
                $user->password_hash = Yii::$app->security->generatePasswordHash($password);
                $user->created_at = time();
                $user->updated_at = time();
                $user->status = 1; // Ativo
                
                if ($user->save(false)) {
                    return ['success' => true, 'message' => 'Usuário criado com sucesso.'];
                }
            } else {
                // Edição de Usuário Existente
                $user = User::findOne($id);
                if (!$user) {
                    return ['success' => false, 'message' => 'Usuário não encontrado.'];
                }

                if ($username !== $user->username) {
                    $exists = User::find()->where(['username' => $username])->andWhere(['!=', 'id', $id])->one();
                    if ($exists) {
                        return ['success' => false, 'message' => 'Este nome de usuário já está em uso.'];
                    }
                    $user->username = $username;
                }

                if (!empty($password)) {
                    $user->password_hash = Yii::$app->security->generatePasswordHash($password);
                }

                $user->updated_at = time();
                if ($user->save(false)) {
                    return ['success' => true, 'message' => 'Usuário atualizado com sucesso.'];
                }
            }
        }

        return ['success' => false, 'message' => 'Requisição inválida.'];
    }

    /**
     * Alterna o status de bloqueio de um usuário
     */
    public function actionToggleUserStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = (int)$id;
        $currentUserId = (int)Yii::$app->user->id;

        if ($id === $currentUserId) {
            return ['success' => false, 'message' => 'Você não pode bloquear a sua própria conta de administrador.'];
        }

        $user = User::findOne($id);
        if (!$user) {
            return ['success' => false, 'message' => 'Usuário não encontrado.'];
        }

        $user->status = $user->status === 1 ? 0 : 1;
        $user->updated_at = time();
        if ($user->save(false)) {
            $msg = $user->status === 1 ? 'Usuário desbloqueado com sucesso.' : 'Usuário bloqueado com sucesso.';
            return ['success' => true, 'message' => $msg, 'status' => $user->status];
        }

        return ['success' => false, 'message' => 'Falha ao alterar o status do usuário.'];
    }

    /**
     * Atualiza as permissões RBAC de um usuário
     */
    public function actionUpdateUserPermissions()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $db = Yii::$app->db;

        if ($request->isPost) {
            $userId = (string)$request->post('user_id');
            $permissions = $request->post('permissions', []);

            if (empty($userId)) {
                return ['success' => false, 'message' => 'ID do usuário inválido.'];
            }

            // Iniciar transação para atualizar permissões de forma segura
            $transaction = $db->beginTransaction();
            try {
                // 1. Limpar todas as atribuições antigas
                $db->createCommand()->delete('auth_assignment', ['user_id' => $userId])->execute();

                // 2. Inserir as novas atribuições selecionadas
                $time = time();
                foreach ($permissions as $permName) {
                    $db->createCommand()->insert('auth_assignment', [
                        'item_name' => $permName,
                        'user_id' => $userId,
                        'created_at' => $time
                    ])->execute();
                }

                $transaction->commit();
                return ['success' => true, 'message' => 'Permissões atualizadas com sucesso.'];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['success' => false, 'message' => 'Erro ao salvar permissões: ' . $e->getMessage()];
            }
        }
        return ['success' => false, 'message' => 'Requisição inválida.'];
    }

    /**
     * Salva as configurações dinâmicas do Dashboard
     */
    public function actionSaveSettings()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $db = Yii::$app->db;

        if ($request->isPost) {
            $settings = $request->post('settings', []);
            $transaction = $db->beginTransaction();
            try {
                foreach ($settings as $key => $value) {
                    $db->createCommand("
                        INSERT OR REPLACE INTO core_settings (key, value)
                        VALUES (:key, :value)
                    ", [
                        ':key' => $key,
                        ':value' => trim((string)$value)
                    ])->execute();
                }
                $transaction->commit();
                return ['success' => true, 'message' => 'Configurações salvas com sucesso!'];
            } catch (\Exception $e) {
                $transaction->rollBack();
                return ['success' => false, 'message' => 'Erro ao salvar configurações: ' . $e->getMessage()];
            }
        }
        return ['success' => false, 'message' => 'Requisição inválida.'];
    }

    /**
     * Ativa/desativa um módulo integrado
     */
    public function actionToggleModule($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $db = Yii::$app->db;

        // O admin não deve desativar o módulo de admin!
        if ($id === 'admin') {
            return ['success' => false, 'message' => 'Não é permitido desativar o módulo de administração.'];
        }

        $module = $db->createCommand("SELECT * FROM core_modules WHERE id = :id", [':id' => $id])->queryOne();
        if (!$module) {
            return ['success' => false, 'message' => 'Módulo não encontrado.'];
        }

        $newStatus = $module['is_active'] ? 0 : 1;
        $db->createCommand()->update('core_modules', [
            'is_active' => $newStatus
        ], ['id' => $id])->execute();

        $msg = $newStatus ? 'Módulo ativado com sucesso.' : 'Módulo desativado com sucesso.';
        return ['success' => true, 'message' => $msg, 'is_active' => $newStatus];
    }

    /**
     * Retorna o log do sistema de forma otimizada (últimas 150 linhas)
     */
    public function actionGetLogs()
    {
        $logFile = Yii::getAlias('@runtime/logs/app.log');
        $rawLogs = $this->readLastLines($logFile, 150);
        
        // Tratar caracteres especiais para renderização segura no terminal
        return '<pre class="text-[10px] font-mono text-slate-300 leading-normal whitespace-pre-wrap select-text">' . Html::encode($rawLogs) . '</pre>';
    }

    /**
     * Função auxiliar de leitura reversa e incremental de logs de alta performance
     */
    private function readLastLines($filename, $lines = 150)
    {
        if (!is_file($filename)) {
            return "Nenhum arquivo de log gravado em: " . basename($filename);
        }

        $handle = fopen($filename, "r");
        if (!$handle) {
            return "Erro de E/S de arquivo: Sem permissão de leitura nos logs.";
        }

        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = [];

        while ($linecounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $linecounter--;
            if ($beginning) {
                rewind($handle);
            }
            $line = fgets($handle);
            if ($line !== false) {
                $text[] = $line;
            }
            if ($beginning) {
                break;
            }
        }
        fclose($handle);
        return implode("", array_reverse($text));
    }
}
