<?php

declare(strict_types=1);

namespace app\modules\p2p\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Exige usuário autenticado no portal
                    ],
                ],
            ],
        ];
    }

    /**
     * Interceptador de layout inteligente para suporte ao HTMX/SPA
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
     * Renderiza o iframe
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'url' => 'https://crom.run/p2pfile'
        ]);
    }
}
