<?php
return [
    'name'  => 'City of Sibley: Highlight of Iowa',
    'timezone' => 'America/Chicago',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin/staff/<action:(index|create|update|delete)>' => 'staff/<action>',
                'admin/page/<action:(index|create|update|delete)>/<id:\d+>' => 'page/<action>',
                //'admin/category/<action:(index|create|update|delete)>/<id:\d+>' => 'category/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:[-\w]+>/<action:[-\w]+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[-\w]+>/<action:[-\w]+>' => '<controller>/<action>',
            ],
        ],
        'formatter' => [
            'dateFormat' => 'MM/dd/yyyy',
            'datetimeFormat' => 'php:m.d.Y H:i:s',
        ],
        'feed' =>[
            'class' => 'yii\feed\FeedDriver',
        ],
    ],
    //'modules' => [
    //    'rbac' => [
    //        'class' => 'yii2mod\rbac\Module',
    //    ],
    //],
    
];
/*Config notes*/
//https://stackoverflow.com/questions/50600193/yii2-frontend-to-backend-and-backend-to-frontend-controller-config-files-ht