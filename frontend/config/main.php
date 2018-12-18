<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            //'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
            //'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //https://stackoverflow.com/questions/28118691/yii2-htaccess-how-to-hide-frontend-web-and-backend-web-completely/49826324#49826324
/*
        // main - used to generate and parse URLs to frontend from frontend app
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
            'baseUrl' => '/',
            'rules' => [
                '/' => 'site/index',
                'home' => 'site/home',
                'about' => 'site/about',
                'contact' => 'site/contact',
                //'login' => 'site/login',
                'signup' => 'site/signup',
            ],
        ],
        // slave - used to generate URLs to backend from frontend app
        'urlManagerBackend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/admin',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ], */
    ],
    'params' => $params,
];
