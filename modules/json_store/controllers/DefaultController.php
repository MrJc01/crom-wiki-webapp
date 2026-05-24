<?php

namespace app\modules\json_store\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use app\modules\json_store\models\JsonEndpoint;
use app\modules\json_store\models\JsonStoreToken;
use app\modules\json_store\models\JsonAccessLog;
use app\models\User;

/**
 * DefaultController — Interface SPA para membros logados (CRUD completo)
 */
class DefaultController extends Controller
{
    /**
     * Interceptador para desativar layouts em requisições HTMX.
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
                        'roles' => ['access-wiki'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lista todos os endpoints com stats.
     */
    public function actionIndex()
    {
        $endpoints = JsonEndpoint::find()->orderBy(['updated_at' => SORT_DESC])->all();

        $endpointsArray = [];
        foreach ($endpoints as $ep) {
            $adminNames = [];
            $adminIds = [];
            foreach ($ep->adminUsers as $user) {
                $adminNames[] = $user->username;
                $adminIds[] = $user->id;
            }

            $tokenCount = JsonStoreToken::find()->where(['endpoint_id' => $ep->id])->count();

            $endpointsArray[] = [
                'id' => $ep->id,
                'slug' => $ep->slug,
                'name' => $ep->name,
                'json_content' => $ep->json_content,
                'is_public' => $ep->is_public,
                'created_by' => $ep->created_by,
                'category' => $ep->category,
                'admin_ids' => $adminIds,
                'admin_name' => !empty($adminNames) ? implode(', ', $adminNames) : 'Nenhum',
                'total_requests' => $ep->getTotalRequests(),
                'requests_24h' => $ep->getRequests24h(),
                'token_count' => (int)$tokenCount,
                'updated_at' => date('d/m/Y H:i:s', $ep->updated_at),
            ];
        }

        $categories = JsonEndpoint::find()
            ->select('category')
            ->distinct()
            ->column();

        if (empty($categories)) {
            $categories = ['Geral', 'Mobile', 'Infraestrutura', 'Frontend'];
        }

        $viewData = [
            'endpoints' => $endpointsArray,
            'categories' => $categories,
        ];

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('index', $viewData);
        }

        return $this->render('index', $viewData);
    }

    /**
     * Salva ou cria um endpoint JSON.
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $id = $request->post('id');
        $slug = $request->post('slug');
        $name = $request->post('name');
        $jsonContent = $request->post('json_content');
        $category = $request->post('category') ?: 'Geral';
        $isPublic = (int)($request->post('is_public') !== null ? $request->post('is_public') : 1);

        // Coleta múltiplos IDs de administradores
        $adminIdsRaw = $request->post('admin_ids');
        $adminIds = [];
        if (!empty($adminIdsRaw)) {
            if (is_string($adminIdsRaw)) {
                $adminIds = array_filter(explode(',', $adminIdsRaw));
            } elseif (is_array($adminIdsRaw)) {
                $adminIds = $adminIdsRaw;
            }
        }

        if (empty($slug) || empty($name)) {
            return ['success' => false, 'message' => 'Campos Slug e Nome são obrigatórios.'];
        }

        // Valida JSON antes de salvar
        if (!empty($jsonContent)) {
            json_decode($jsonContent);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['success' => false, 'message' => 'JSON inválido: ' . json_last_error_msg()];
            }
        } else {
            $jsonContent = '{}';
        }

        $isNew = false;
        if (!empty($id)) {
            $endpoint = JsonEndpoint::findOne($id);
            if (!$endpoint) {
                return ['success' => false, 'message' => 'Endpoint não encontrado para edição.'];
            }
            // Validação BOLA
            $isAdmin = Yii::$app->user->can('admin-access');
            $isCreator = ($endpoint->created_by === Yii::$app->user->identity->username);
            $isAuthorizedAdmin = in_array((int)Yii::$app->user->id, $endpoint->adminIds);

            if (!$isAdmin && !$isCreator && !$isAuthorizedAdmin) {
                return ['success' => false, 'message' => 'Você não tem permissão para editar este endpoint.'];
            }
        } else {
            $isNew = true;
            $endpoint = new JsonEndpoint();
            $endpoint->created_by = Yii::$app->user->identity ? Yii::$app->user->identity->username : 'Sistema';
        }

        $endpoint->slug = $slug;
        $endpoint->name = $name;
        $endpoint->json_content = $jsonContent;
        $endpoint->category = $category;
        $endpoint->is_public = $isPublic;
        $endpoint->adminIds = $adminIds;

        if ($endpoint->save()) {
            return [
                'success' => true,
                'message' => $isNew ? 'Endpoint JSON criado com sucesso!' : 'Endpoint JSON salvo com sucesso!',
                'id' => $endpoint->id,
                'slug' => $endpoint->slug,
            ];
        }

        $errors = $endpoint->getErrors();
        $firstError = reset($errors);
        $msg = is_array($firstError) ? reset($firstError) : 'Erro desconhecido ao salvar.';

        return ['success' => false, 'message' => $msg];
    }

    /**
     * Exclui um endpoint JSON.
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $endpoint = JsonEndpoint::findOne($id);
        if (!$endpoint) {
            return ['success' => false, 'message' => 'Endpoint não encontrado.'];
        }

        // Validação BOLA
        $isAdmin = Yii::$app->user->can('admin-access');
        $isCreator = ($endpoint->created_by === Yii::$app->user->identity->username);
        $isAuthorizedAdmin = in_array((int)Yii::$app->user->id, $endpoint->adminIds);

        if (!$isAdmin && !$isCreator && !$isAuthorizedAdmin) {
            return ['success' => false, 'message' => 'Você não tem permissão para excluir este endpoint.'];
        }

        if ($endpoint->delete()) {
            return ['success' => true, 'message' => 'Endpoint JSON excluído com sucesso!'];
        }

        return ['success' => false, 'message' => 'Falha ao excluir o endpoint.'];
    }

    /**
     * Gera um novo token de acesso para um endpoint.
     */
    public function actionGenerateToken()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;
        $endpointId = (int)$request->post('endpoint_id');
        $label = $request->post('label') ?: 'Token ' . date('d/m/Y H:i');

        $endpoint = JsonEndpoint::findOne($endpointId);
        if (!$endpoint) {
            return ['success' => false, 'message' => 'Endpoint não encontrado.'];
        }

        // Validação BOLA
        $isAdmin = Yii::$app->user->can('admin-access');
        $isCreator = ($endpoint->created_by === Yii::$app->user->identity->username);
        $isAuthorizedAdmin = in_array((int)Yii::$app->user->id, $endpoint->adminIds);

        if (!$isAdmin && !$isCreator && !$isAuthorizedAdmin) {
            return ['success' => false, 'message' => 'Você não tem permissão para gerar tokens neste endpoint.'];
        }

        // Gera token raw (exibido uma única vez)
        $tokenRaw = Yii::$app->security->generateRandomString(32);
        $tokenHash = hash('sha256', $tokenRaw);

        $token = new JsonStoreToken();
        $token->endpoint_id = $endpointId;
        $token->token_hash = $tokenHash;
        $token->label = $label;
        $token->created_at = time();

        if ($token->save()) {
            return [
                'success' => true,
                'token' => $tokenRaw,
                'token_id' => $token->id,
                'message' => 'Token gerado! ATENÇÃO: Copie agora, ele não será exibido novamente.',
            ];
        }

        return ['success' => false, 'message' => 'Falha ao gerar o token.'];
    }

    /**
     * Revoga (exclui) um token de acesso.
     */
    public function actionRevokeToken($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $token = JsonStoreToken::findOne($id);
        if (!$token) {
            return ['success' => false, 'message' => 'Token não encontrado.'];
        }

        $endpoint = $token->endpoint;
        if (!$endpoint) {
            return ['success' => false, 'message' => 'Endpoint associado não encontrado.'];
        }

        // Validação BOLA
        $isAdmin = Yii::$app->user->can('admin-access');
        $isCreator = ($endpoint->created_by === Yii::$app->user->identity->username);
        $isAuthorizedAdmin = in_array((int)Yii::$app->user->id, $endpoint->adminIds);

        if (!$isAdmin && !$isCreator && !$isAuthorizedAdmin) {
            return ['success' => false, 'message' => 'Você não tem permissão para revogar tokens deste endpoint.'];
        }

        if ($token->delete()) {
            return ['success' => true, 'message' => 'Token revogado com sucesso!'];
        }

        return ['success' => false, 'message' => 'Falha ao revogar o token.'];
    }

    /**
     * Lista tokens de um endpoint (retorna apenas metadados, nunca o hash).
     */
    public function actionTokensList($endpoint_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $tokens = JsonStoreToken::find()
            ->where(['endpoint_id' => $endpoint_id])
            ->select(['id', 'label', 'created_at'])
            ->asArray()
            ->all();

        foreach ($tokens as &$t) {
            $t['created_at_fmt'] = date('d/m/Y H:i', $t['created_at']);
        }

        return $tokens;
    }

    /**
     * Lista de usuários para seleção de administradores.
     */
    public function actionUsersList()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return User::find()->select(['id', 'username'])->asArray()->all();
    }
}
