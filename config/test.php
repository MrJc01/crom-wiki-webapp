<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/test_db.php';

/**
 * Application configuration shared by all test types
 */
return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        \app\tests\Support\MailerBootstrap::class,
        \app\components\ModuleLoader::class,
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'language' => 'en-US',
    'components' => [
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'messageClass' => \yii\symfonymailer\Message::class,
            'useFileTransport' => true,
            'viewPath' => '@app/mail',
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                'discover' => 'site/discover',
                'dashboard' => 'site/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'beneficios' => 'site/beneficios',
                'projetos' => 'site/projetos',
                'comunidades' => 'site/comunidades',
                'aprendizado' => 'site/aprendizado',
                'wiki' => 'wiki/default/index',
                
                // Módulo page_crud
                'p' => 'page_crud/default/index',
                'p/view/<id:\d+>' => 'page_crud/default/view',
                'p/delete/<id:\d+>' => 'page_crud/default/delete',
                'p/save' => 'page_crud/default/save',
                'p/users' => 'page_crud/default/users-list',
                'p/<slug:.*>' => 'page_crud/default/view-by-slug',

                // Módulo terminal SSH
                'terminal' => 'terminal/default/index',
                'terminal/stream' => 'terminal/default/stream',
                'terminal/write' => 'terminal/default/write',

                // Módulo CromIA
                'cromia' => 'cromia/default/index',
            ],
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
            // but if you absolutely need it set cookie domain to localhost
            /*
            'csrfCookie' => [
                'domain' => 'localhost',
            ],
            */
        ],
    ],
    'params' => $params,
];
