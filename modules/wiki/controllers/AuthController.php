<?php

namespace app\modules\wiki\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use app\modules\wiki\models\WikiGithubAuth;
use app\modules\wiki\components\GithubClient;

/**
 * Controller responsável pelo fluxo de login e callback do GitHub OAuth.
 */
class AuthController extends Controller
{
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
                        'roles' => ['@'], // Exige usuário logado no portal
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Desativa o layout para HTMX ou Ajax
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
     * Inicia o fluxo de autorização do GitHub.
     */
    public function actionLogin()
    {
        $clientId = getenv('GITHUB_CLIENT_ID');
        $redirectUri = getenv('GITHUB_REDIRECT_URI');

        if (empty($clientId)) {
            Yii::$app->session->setFlash('error', 'GITHUB_CLIENT_ID não está configurado.');
            return $this->redirect(['/wiki']);
        }

        // Gera um token de estado (CSRF) para segurança contra CSRF
        $state = Yii::$app->security->generateRandomString(32);
        Yii::$app->session->set('github_oauth_state', $state);

        $url = 'https://github.com/login/oauth/authorize?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'repo',
            'state' => $state,
        ]);

        return $this->redirect($url);
    }

    /**
     * Callback do GitHub OAuth. Recebe a resposta e troca o código pelo token.
     */
    public function actionCallback()
    {
        $request = Yii::$app->request;
        $code = $request->get('code');
        $state = $request->get('state');

        // 1. Validar o token de estado CSRF
        $savedState = Yii::$app->session->get('github_oauth_state');
        Yii::$app->session->remove('github_oauth_state');

        if (empty($state) || $state !== $savedState) {
            Yii::$app->session->setFlash('error', 'Erro de validação de estado (CSRF State Mismatch).');
            return $this->redirect(['/wiki']);
        }

        if (empty($code)) {
            Yii::$app->session->setFlash('error', 'Código de autorização não fornecido pelo GitHub.');
            return $this->redirect(['/wiki']);
        }

        // 2. Trocar código pelo access token
        $tokenResponse = GithubClient::exchangeCodeForToken($code);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            Yii::$app->session->setFlash('error', 'Falha ao obter o Token de Acesso do GitHub.');
            return $this->redirect(['/wiki']);
        }

        $accessToken = $tokenResponse['access_token'];
        $refreshToken = $tokenResponse['refresh_token'] ?? 'none';
        $expiresIn = $tokenResponse['expires_in'] ?? 315360000; // 10 anos se não expirar
        $expiresAt = time() + (int)$expiresIn;

        // 3. Buscar informações do perfil do usuário do GitHub
        $userInfo = GithubClient::getUserInfo($accessToken);
        if (!$userInfo || !isset($userInfo['id']) || !isset($userInfo['login'])) {
            Yii::$app->session->setFlash('error', 'Não foi possível ler as informações do seu perfil do GitHub.');
            return $this->redirect(['/wiki']);
        }

        $ghUserId = (int)$userInfo['id'];
        $ghUsername = $userInfo['login'];

        // 4. Salvar ou atualizar na tabela `wiki_github_auth`
        $userId = Yii::$app->user->id;
        $authModel = WikiGithubAuth::findOne(['user_id' => $userId]);
        if (!$authModel) {
            $authModel = new WikiGithubAuth();
            $authModel->user_id = $userId;
        }

        $secretKey = getenv('WIKI_SECRET_KEY') ?: (Yii::$app->request->cookieValidationKey ?: 'default_secret_key_32_chars_long_!!');

        $authModel->gh_user_id = $ghUserId;
        $authModel->gh_username = $ghUsername;
        $authModel->expires_at = $expiresAt;
        
        $authModel->setEncryptedAccessToken($accessToken, $secretKey);
        $authModel->setEncryptedRefreshToken($refreshToken, $secretKey);

        if ($authModel->save()) {
            Yii::$app->session->setFlash('success', "GitHub conectado com sucesso como @{$ghUsername}!");
        } else {
            Yii::error('Erro ao salvar credenciais do GitHub no banco: ' . json_encode($authModel->errors), __METHOD__);
            Yii::$app->session->setFlash('error', 'Ocorreu um erro ao salvar suas credenciais locais da integração.');
        }

        return $this->redirect(['/wiki']);
    }

    /**
     * Retorna o status de conexão em formato JSON.
     */
    public function actionStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $userId = Yii::$app->user->id;
        $authModel = WikiGithubAuth::findOne(['user_id' => $userId]);

        $owner = getenv('GITHUB_REPO_OWNER') ?: 'MrJc01';
        $repo = getenv('GITHUB_REPO_NAME') ?: 'crom-wiki';

        if ($authModel) {
            return [
                'connected' => true,
                'username' => $authModel->gh_username,
                'repo' => "{$owner}/{$repo}",
            ];
        }

        return [
            'connected' => false,
            'repo' => "{$owner}/{$repo}",
        ];
    }

    /**
     * Remove o vínculo com a conta do GitHub.
     */
    public function actionDisconnect()
    {
        $userId = Yii::$app->user->id;
        $authModel = WikiGithubAuth::findOne(['user_id' => $userId]);
        
        if ($authModel && $authModel->delete()) {
            Yii::$app->session->setFlash('success', 'Conta do GitHub desconectada com sucesso.');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao desconectar conta do GitHub.');
        }

        return $this->redirect(['/wiki']);
    }
}
