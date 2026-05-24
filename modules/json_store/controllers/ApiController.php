<?php

namespace app\modules\json_store\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\modules\json_store\models\JsonEndpoint;
use app\modules\json_store\models\JsonStoreToken;
use app\modules\json_store\models\JsonAccessLog;

/**
 * ApiController — Endpoint REST público para servir JSONs via API.
 * 
 * Acesso: Público (sem sessão, sem layout Yii2).
 * Rota: GET /api/json/{slug}
 * 
 * - Endpoints públicos: Acesso livre.
 * - Endpoints privados: Requer header "Authorization: Bearer {token}".
 */
class ApiController extends Controller
{
    /**
     * Desabilita CSRF para requisições de API REST.
     */
    public $enableCsrfValidation = false;

    /**
     * Remove layout para respostas puramente JSON.
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->layout = false;
            return true;
        }
        return false;
    }

    /**
     * Sem AccessControl RBAC — Endpoint totalmente público.
     * A autenticação é feita manualmente via Bearer Token.
     */
    public function behaviors()
    {
        return [
            // Sem filtro de access control — API pública
        ];
    }

    /**
     * Serve o JSON de um endpoint por slug.
     * 
     * @param string $slug O slug único do endpoint
     * @return array O conteúdo JSON parseado
     */
    public function actionServe($slug)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Configurações CORS básicas para acesso cross-origin
        $headers = Yii::$app->response->headers;
        $headers->set('Access-Control-Allow-Origin', '*');
        $headers->set('Access-Control-Allow-Headers', 'Authorization, Content-Type');
        $headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');

        // Responde OPTIONS para preflight CORS
        if (Yii::$app->request->method === 'OPTIONS') {
            Yii::$app->response->statusCode = 204;
            return '';
        }

        // Busca o endpoint pelo slug
        $endpoint = JsonEndpoint::findOne(['slug' => $slug]);
        if (!$endpoint) {
            Yii::$app->response->statusCode = 404;
            return [
                'error' => true,
                'status' => 404,
                'message' => 'Endpoint JSON não encontrado.',
            ];
        }

        // Se privado, valida Bearer Token
        if (!$endpoint->is_public) {
            $authHeader = Yii::$app->request->headers->get('Authorization');

            if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
                Yii::$app->response->statusCode = 401;
                return [
                    'error' => true,
                    'status' => 401,
                    'message' => 'Acesso negado. Este endpoint requer autenticação via Bearer Token.',
                    'hint' => 'Envie o header: Authorization: Bearer {seu_token}',
                ];
            }

            $tokenRaw = trim(substr($authHeader, 7));
            $tokenHash = hash('sha256', $tokenRaw);

            // Busca o token pelo hash para evitar timing attacks
            $validToken = JsonStoreToken::findOne([
                'endpoint_id' => $endpoint->id,
                'token_hash' => $tokenHash,
            ]);

            if (!$validToken) {
                Yii::$app->response->statusCode = 403;
                return [
                    'error' => true,
                    'status' => 403,
                    'message' => 'Token de acesso inválido ou revogado.',
                ];
            }
        }

        // Logar acesso
        try {
            $log = new JsonAccessLog();
            $log->endpoint_id = $endpoint->id;
            $log->ip_address = Yii::$app->request->userIP;
            $log->user_agent = substr(Yii::$app->request->userAgent ?? '', 0, 500);
            $log->accessed_at = time();
            $log->save(false);
        } catch (\Exception $e) {
            // Silencia falhas de log para não impactar a resposta da API
        }

        // Retorna o JSON parseado diretamente
        $decoded = json_decode($endpoint->json_content, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            // Se o JSON estiver corrompido, retorna como string raw
            return ['data' => $endpoint->json_content];
        }

        return $decoded;
    }
}
