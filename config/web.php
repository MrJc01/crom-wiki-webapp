<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', \app\components\ModuleLoader::class],
    'container' => [
        'singletons' => [
            \yii\mail\MailerInterface::class => [
                'class' => \yii\symfonymailer\Mailer::class,
                // send all mails to a file by default.
                'useFileTransport' => true,
                'viewPath' => '@app/mail',
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY') ?: 'insira_uma_chave_aleatoria_com_mais_de_32_caracteres',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => \yii\mail\MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
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
                'wiki/auth/login' => 'wiki/auth/login',
                'wiki/auth/callback' => 'wiki/auth/callback',
                'wiki/auth/status' => 'wiki/auth/status',
                'wiki/auth/disconnect' => 'wiki/auth/disconnect',
                
                // Módulo page_crud
                'p' => 'page_crud/default/index',
                'p/view/<id:\d+>' => 'page_crud/default/view',
                'p/delete/<id:\d+>' => 'page_crud/default/delete',
                'p/save' => 'page_crud/default/save',
                'p/users' => 'page_crud/default/users-list',
                'p/<slug:.*>' => 'page_crud/default/view-by-slug',

                // Módulo json_store
                'json' => 'json_store/default/index',
                'json/save' => 'json_store/default/save',
                'json/delete/<id:\d+>' => 'json_store/default/delete',
                'json/generate-token' => 'json_store/default/generate-token',
                'json/revoke-token/<id:\d+>' => 'json_store/default/revoke-token',
                'json/tokens-list' => 'json_store/default/tokens-list',
                'json/users' => 'json_store/default/users-list',
                
                // API REST pública — JSON Store
                'api/json/<slug:[\w\-]+>' => 'json_store/api/serve',

                // Módulo terminal SSH
                'terminal' => 'terminal/default/index',
                'terminal/stream' => 'terminal/default/stream',
                'terminal/write' => 'terminal/default/write',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
