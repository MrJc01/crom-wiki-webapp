<?php

declare(strict_types=1);

namespace app\modules\welcome\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * Intercetor de segurança e layouts para HTMX
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (Yii::$app->user->isGuest) {
                return $this->redirect(['/site/login'])->send();
            }

            if (!Yii::$app->user->can('admin-access')) {
                throw new ForbiddenHttpException('Você não tem permissão para acessar esta área.');
            }

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
     * Listagem dos Sliders/Badges
     */
    public function actionIndex(): string
    {
        $db = Yii::$app->db;
        $sliders = $db->createCommand("SELECT * FROM welcome_sliders ORDER BY id DESC")->queryAll();

        // Coagir o status is_active para booleano
        foreach ($sliders as $k => $s) {
            $sliders[$k]['is_active'] = (bool)(int)$s['is_active'];
            $sliders[$k]['slides'] = json_decode($s['slides_json'], true) ?: [];
        }

        return $this->render('index', [
            'sliders' => $sliders
        ]);
    }

    /**
     * Ação de Salvar / Criar
     */
    public function actionSave()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $request = Yii::$app->request;

        if ($request->isPost) {
            $id = $request->post('id');
            $title = trim((string)$request->post('title'));
            $badgeText = trim((string)$request->post('badge_text'));
            $icon = trim((string)$request->post('icon', '👋'));
            $requiredRole = trim((string)$request->post('required_role', 'all'));
            $isActive = $request->post('is_active') ? 1 : 0;
            $slidesJson = $request->post('slides_json', '[]');

            if (empty($title)) {
                return ['success' => false, 'message' => 'O título identificador é obrigatório.'];
            }

            // Validar slides_json
            $decoded = json_decode($slidesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ['success' => false, 'message' => 'Erro nos slides: JSON inválido.'];
            }

            $db = Yii::$app->db;
            $time = time();

            if (empty($id)) {
                // Criar Novo
                $db->createCommand()->insert('welcome_sliders', [
                    'title' => $title,
                    'badge_text' => $badgeText ?: null,
                    'icon' => $icon,
                    'required_role' => $requiredRole,
                    'is_active' => $isActive,
                    'slides_json' => json_encode($decoded, JSON_UNESCAPED_UNICODE),
                    'created_at' => $time,
                    'updated_at' => $time,
                ])->execute();

                return ['success' => true, 'message' => 'Experiência de boas-vindas criada com sucesso.'];
            } else {
                // Atualizar Existente
                $exists = $db->createCommand("SELECT COUNT(*) FROM welcome_sliders WHERE id = :id", [':id' => $id])->queryScalar();
                if (!$exists) {
                    return ['success' => false, 'message' => 'Experiência não encontrada.'];
                }

                $db->createCommand()->update('welcome_sliders', [
                    'title' => $title,
                    'badge_text' => $badgeText ?: null,
                    'icon' => $icon,
                    'required_role' => $requiredRole,
                    'is_active' => $isActive,
                    'slides_json' => json_encode($decoded, JSON_UNESCAPED_UNICODE),
                    'updated_at' => $time,
                ], ['id' => $id])->execute();

                return ['success' => true, 'message' => 'Experiência de boas-vindas atualizada com sucesso.'];
            }
        }

        return ['success' => false, 'message' => 'Requisição inválida.'];
    }

    /**
     * Alternar status is_active
     */
    public function actionToggle($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $slider = $db->createCommand("SELECT * FROM welcome_sliders WHERE id = :id", [':id' => $id])->queryOne();
        if (!$slider) {
            return ['success' => false, 'message' => 'Experiência não encontrada.'];
        }

        $newStatus = $slider['is_active'] ? 0 : 1;
        $db->createCommand()->update('welcome_sliders', [
            'is_active' => $newStatus
        ], ['id' => $id])->execute();

        $msg = $newStatus ? 'Experiência ativada com sucesso.' : 'Experiência desativada com sucesso.';
        return ['success' => true, 'message' => $msg, 'is_active' => (bool)$newStatus];
    }

    /**
     * Excluir
     */
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $db = Yii::$app->db;

        $exists = $db->createCommand("SELECT COUNT(*) FROM welcome_sliders WHERE id = :id", [':id' => $id])->queryScalar();
        if (!$exists) {
            return ['success' => false, 'message' => 'Experiência não encontrada.'];
        }

        $db->createCommand()->delete('welcome_sliders', ['id' => $id])->execute();

        return ['success' => true, 'message' => 'Experiência excluída com sucesso.'];
    }
}
