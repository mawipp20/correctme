<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'homeUrl' => ['student/index'],
    'defaultRoute' => 'student/index',
    'bootstrap' => ['log'],
    'language' => 'de',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'CUfeeMJnPbuDHlrF4jM7v1NaPQOozTgR',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        '_L' => [
            'class' => 'app\components\Language',
        ],
        'resultsDisplay' => [
            'class' => 'app\components\ResultsDisplay',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
            
                //YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                YII_ENV_DEV ? '' : 
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,   // do not publish the bundle
                    'js' => [
                        '//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js',
                    ]
                ],
            ],



        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                "team" => "site/team",
            /**
                "UserForm" => "site/userform",
                "hello" => "site/hello",
                ['class' => 'yii\rest\UrlRule', 'controller' => ['book'], 'pluralize' => true],
            **/
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
