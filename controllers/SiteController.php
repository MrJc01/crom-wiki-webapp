<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use app\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * Interceptador de layout inteligente para abas do portal
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->updateUserActivity();
            
            // Determina se deve carregar apenas parcial (HTMX) ou layout completo (acesso direto)
            $ajaxActions = ['discover', 'beneficios', 'projetos', 'aprendizado', 'comunidades', 'profile'];
            if (in_array($action->id, $ajaxActions)) {
                if (Yii::$app->request->headers->has('HX-Request')) {
                    $this->layout = false;
                } else {
                    $this->layout = 'main';
                }
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'], // Apenas usuários autenticados
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post', 'get'], // Permite ambos para facilidade de uso
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage (Smart Routing).
     *
     * @return string
     */
    public function actionIndex(): string
    {
        if (Yii::$app->user->isGuest) {
            // Se for convidado, renderiza a landing page pública
            return $this->render('index');
        }

        // Se estiver logado, atualiza atividade e renderiza o Dashboard interno
        $this->updateUserActivity();

        return $this->render('dashboard');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $this->updateUserActivity();
            return $this->goHome();
        }

        $model->password = '';

        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->id;
            try {
                Yii::$app->db->createCommand("
                    INSERT OR REPLACE INTO core_session_status (user_id, last_activity, is_online)
                    VALUES (:userId, :time, 0)
                ", [
                    ':userId' => $userId,
                    ':time' => time()
                ])->execute();
            } catch (\Exception $e) {
                // Silencia falhas
            }
        }

        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Discover tab action (HTMX partial).
     */
    public function actionDiscover(): string
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }
        
        // Busca todos os módulos ativos cadastrados no banco
        $modules = [];
        try {
            $modules = Yii::$app->db->createCommand("
                SELECT id, name, icon, entry_point, required_permission 
                FROM core_modules 
                WHERE is_active = 1 
                ORDER BY sort_order ASC
            ")->queryAll();
        } catch (\Exception $e) {
            // Fallback
        }

        return $this->render('discover', [
            'modules' => $modules
        ]);
    }

    /**
     * Beneficios tab action (HTMX partial).
     */
    public function actionBeneficios(): string
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }
        return $this->render('beneficios');
    }

    /**
     * Projetos tab action (HTMX partial).
     */
    public function actionProjetos(): string
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }
        return $this->render('projetos');
    }

    /**
     * Aprendizado tab action (HTMX partial).
     */
    public function actionAprendizado(): string
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }
        return $this->render('aprendizado');
    }

    /**
     * Comunidades tab action (HTMX partial).
     */
    public function actionComunidades(): string
    {
        if (Yii::$app->user->isGuest) {
            return '';
        }
        return $this->render('comunidades');
    }

    /**
     * Profile and settings action (SPA compatible).
     */
    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $user = \app\models\User::findOne(Yii::$app->user->id);
        if (!$user) {
            return $this->goHome();
        }

        $request = Yii::$app->request;

        if ($request->isPost) {
            $actionType = $request->post('action_type');
            if ($actionType === 'update_profile') {
                $username = trim((string)$request->post('username'));
                if ($username === '') {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'error',
                        'message' => 'O nome de usuário não pode estar em branco.'
                    ]);
                }
                if ($username !== $user->username) {
                    $existing = \app\models\User::find()->where(['username' => $username])->andWhere(['!=', 'id', $user->id])->one();
                    if ($existing) {
                        return $this->renderPartial('_profile_alert', [
                            'type' => 'error',
                            'message' => 'Este nome de usuário já está em uso.'
                        ]);
                    }
                    $user->username = $username;
                    $user->updated_at = time();
                    if ($user->save(false)) {
                        // Envia trigger HTMX para atualizar elementos do DOM que usam o username
                        Yii::$app->response->headers->set('HX-Trigger', 'usernameUpdated');
                        return $this->renderPartial('_profile_alert', [
                            'type' => 'success',
                            'message' => 'Perfil atualizado com sucesso!'
                        ]);
                    }
                } else {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'info',
                        'message' => 'Nenhuma alteração detectada.'
                    ]);
                }
            } elseif ($actionType === 'change_password') {
                $currentPassword = (string)$request->post('current_password');
                $newPassword = (string)$request->post('new_password');
                $confirmPassword = (string)$request->post('confirm_password');

                if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'error',
                        'message' => 'Todos os campos de senha são obrigatórios.'
                    ]);
                }

                if (!$user->validatePassword($currentPassword)) {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'error',
                        'message' => 'A senha atual está incorreta.'
                    ]);
                }

                if ($newPassword !== $confirmPassword) {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'error',
                        'message' => 'A nova senha e a confirmação de senha não coincidem.'
                    ]);
                }

                if (strlen($newPassword) < 6) {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'error',
                        'message' => 'A nova senha deve ter pelo menos 6 caracteres.'
                    ]);
                }

                $user->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
                $user->updated_at = time();
                if ($user->save(false)) {
                    return $this->renderPartial('_profile_alert', [
                        'type' => 'success',
                        'message' => 'Senha alterada com sucesso!'
                    ]);
                }
            }
            return $this->renderPartial('_profile_alert', [
                'type' => 'error',
                'message' => 'Ação inválida.'
            ]);
        }

        return $this->render('profile', [
            'user' => $user
        ]);
    }

    /**
     * Atualiza o timestamp de atividade do usuário logado na tabela core_session_status
     */
    private function updateUserActivity(): void
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->id;
            try {
                Yii::$app->db->createCommand("
                    INSERT OR REPLACE INTO core_session_status (user_id, last_activity, is_online)
                    VALUES (:userId, :time, 1)
                ", [
                    ':userId' => $userId,
                    ':time' => time()
                ])->execute();
            } catch (\Exception $e) {
                // Silencia falhas de banco
            }
        }
    }
}
