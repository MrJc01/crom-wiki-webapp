<?php

namespace app\modules\page_crud\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\modules\page_crud\models\PageDocumented;
use app\models\User;

/**
 * Default controller for the `page_crud` module.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // Desativa o layout global para retornar apenas o HTML parcial SPA
        $this->layout = false;
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
                        'actions' => ['view-by-slug'],
                        'allow' => true,
                        'roles' => ['?', '@'], // Permite acesso público a convidados e logados
                    ],
                    [
                        'allow' => true,
                        'roles' => ['access-wiki'], // Exige login para todas as outras ações (save, delete, index, etc.)
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module.
     */
    public function actionIndex()
    {
        // Busca todas as páginas ordenadas para renderizar no front-end
        $pages = PageDocumented::find()->orderBy(['updated_at' => SORT_DESC])->all();
        
        $pagesArray = [];
        foreach ($pages as $p) {
            $adminNames = [];
            $adminIds = [];
            foreach ($p->adminUsers as $user) {
                $adminNames[] = $user->username;
                $adminIds[] = $user->id;
            }

            $pagesArray[] = [
                'id' => $p->id,
                'slug' => $p->slug,
                'title' => $p->title,
                'content' => $p->content,
                'category' => $p->category,
                'is_public' => $p->is_public,
                'created_by' => $p->created_by,
                'admin_ids' => $adminIds,
                'admin_name' => !empty($adminNames) ? implode(', ', $adminNames) : 'Nenhum',
                'updated_at' => date('d/m/Y H:i:s', $p->updated_at)
            ];
        }

        // Extrai categorias exclusivas criadas no banco de dados para os badges de atalho
        $categories = PageDocumented::find()
            ->select('category')
            ->distinct()
            ->column();

        // Insere 'Todos' e garante categorias padrão se estiver vazio
        if (empty($categories)) {
            $categories = ['Geral', 'Produtividade', 'Desenvolvedor', 'Segurança'];
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', [
                'pages' => $pagesArray,
                'categories' => $categories,
                'selectedPageId' => null,
                'selectedPage' => null
            ]);
        }

        return $this->render('index', [
            'pages' => $pagesArray,
            'categories' => $categories,
            'selectedPageId' => null,
            'selectedPage' => null
        ]);
    }

    /**
     * Retorna os detalhes de uma página em JSON.
     */
    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $page = PageDocumented::findOne($id);
        if (!$page) {
            throw new NotFoundHttpException('Página documentada não encontrada.');
        }

        $adminNames = [];
        $adminIds = [];
        foreach ($page->adminUsers as $user) {
            $adminNames[] = $user->username;
            $adminIds[] = $user->id;
        }

        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $page->title,
            'content' => $page->content,
            'category' => $page->category,
            'is_public' => $page->is_public,
            'created_by' => $page->created_by,
            'admin_ids' => $adminIds,
            'admin_name' => !empty($adminNames) ? implode(', ', $adminNames) : 'Nenhum',
            'updated_at' => date('d/m/Y H:i:s', $page->updated_at)
        ];
    }

    /**
     * Salva ou cria uma página documentada diretamente no banco de dados.
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $id = $request->post('id') ?: $request->get('id');
        $slug = $request->post('slug') ?: $request->get('slug');
        $title = $request->post('title') ?: $request->get('title');
        $content = $request->post('content') ?: $request->get('content');
        $category = $request->post('category') ?: ($request->get('category') ?: 'Geral');
        $is_public = (int)($request->post('is_public') !== null ? $request->post('is_public') : ($request->get('is_public') ?: 0));
        
        // Coleta múltiplos IDs de donos de forma resiliente
        $adminIdsRaw = $request->post('admin_ids') ?: $request->get('admin_ids');
        $adminIds = [];
        if (!empty($adminIdsRaw)) {
            if (is_string($adminIdsRaw)) {
                $adminIds = array_filter(explode(',', $adminIdsRaw));
            } else if (is_array($adminIdsRaw)) {
                $adminIds = $adminIdsRaw;
            }
        }

        if (empty($slug) || empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Campos Slug, Título e Conteúdo são obrigatórios.'];
        }

        $isNew = false;
        if (!empty($id)) {
            $page = PageDocumented::findOne($id);
            if (!$page) {
                return ['success' => false, 'message' => 'Página não encontrada para edição.'];
            }
        } else {
            $isNew = true;
            $page = new PageDocumented();
            $page->created_by = Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Sistema';
        }

        $page->slug = $slug;
        $page->title = $title;
        $page->content = $content;
        $page->category = $category;
        $page->is_public = $is_public;
        
        // Propriedade virtual para salvar N:N de forma transacional no afterSave()
        $page->adminIds = $adminIds;

        if ($page->save()) {
            return [
                'success' => true,
                'message' => $isNew ? 'Página documentada criada com sucesso!' : 'Página documentada salva com sucesso!',
                'id' => $page->id,
                'slug' => $page->slug
            ];
        }

        // Pega os erros de validação do ActiveRecord para exibir no front-end
        $errors = $page->getErrors();
        $firstError = reset($errors);
        $msg = is_array($firstError) ? reset($firstError) : 'Erro desconhecido ao salvar.';
        
        return ['success' => false, 'message' => $msg];
    }

    /**
     * Exclui uma página documentada.
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $page = PageDocumented::findOne($id);
        if (!$page) {
            return ['success' => false, 'message' => 'Página não encontrada para exclusão.'];
        }

        if ($page->delete()) {
            return ['success' => true, 'message' => 'Página documentada excluída com sucesso!'];
        }

        return ['success' => false, 'message' => 'Falha ao excluir a página documentada.'];
    }

    /**
     * Retorna a lista de usuários para seleção de administradores.
     */
    public function actionUsersList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return User::find()->select(['id', 'username'])->asArray()->all();
    }

    /**
     * Ação de visualização dinâmica por slug de Pretty URL (Público/Privado)
     */
    public function actionViewBySlug($slug)
    {
        $page = PageDocumented::findOne(['slug' => $slug]);
        if (!$page) {
            throw new NotFoundHttpException('Página documentada não encontrada.');
        }

        // Se a página for privada e o usuário for convidado, redireciona para a tela de login
        if (!$page->is_public && Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        // Se o usuário for convidado (e a página for pública), renderiza a view de leitura pública premium
        if (Yii::$app->user->isGuest) {
            $this->layout = '@app/views/layouts/main'; // Usa o layout limpo para convidados do main.php
            
            $adminNames = [];
            foreach ($page->adminUsers as $user) {
                $adminNames[] = $user->username;
            }

            return $this->render('view_public', [
                'page' => $page,
                'adminNames' => !empty($adminNames) ? implode(', ', $adminNames) : 'Nenhum'
            ]);
        }

        // Se o usuário estiver logado:
        // Se for requisição assíncrona HTMX, renderiza apenas os dados parciais ou o JSON
        if (Yii::$app->request->headers->has('HX-Request')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $adminIds = [];
            $adminNames = [];
            foreach ($page->adminUsers as $user) {
                $adminIds[] = $user->id;
                $adminNames[] = $user->username;
            }
            return [
                'id' => $page->id,
                'slug' => $page->slug,
                'title' => $page->title,
                'content' => $page->content,
                'category' => $page->category,
                'is_public' => $page->is_public,
                'created_by' => $page->created_by,
                'admin_ids' => $adminIds,
                'admin_name' => !empty($adminNames) ? implode(', ', $adminNames) : 'Nenhum',
                'updated_at' => date('d/m/Y H:i:s', $page->updated_at)
            ];
        }

        // Se for carregamento direto pelo navegador do usuário logado:
        // Renderiza o portal completo pré-carregando esta página aberta
        $this->layout = '@app/views/layouts/main';
        
        $pages = PageDocumented::find()->orderBy(['updated_at' => SORT_DESC])->all();
        $pagesArray = [];
        foreach ($pages as $p) {
            $adminNames = [];
            $adminIds = [];
            foreach ($p->adminUsers as $user) {
                $adminNames[] = $user->username;
                $adminIds[] = $user->id;
            }
            $pagesArray[] = [
                'id' => $p->id,
                'slug' => $p->slug,
                'title' => $p->title,
                'content' => $p->content,
                'category' => $p->category,
                'is_public' => $p->is_public,
                'created_by' => $p->created_by,
                'admin_ids' => $adminIds,
                'admin_name' => !empty($adminNames) ? implode(', ', $adminNames) : 'Nenhum',
                'updated_at' => date('d/m/Y H:i:s', $p->updated_at)
            ];
        }

        $categories = PageDocumented::find()->select('category')->distinct()->column();
        if (empty($categories)) {
            $categories = ['Geral', 'Produtividade', 'Desenvolvedor', 'Segurança'];
        }

        return $this->render('index', [
            'pages' => $pagesArray,
            'categories' => $categories,
            'selectedPageId' => $page->id,
            'selectedPage' => $page
        ]);
    }
}
