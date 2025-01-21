<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules'=>[
        'v1'=>[
            'class'=>'app\modules\v1\Module',
        ],
        'v2'=>[
            'class'=>'app\modules\v2\Module',
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Di6nm0CkDRQLFoMNAzQYaTqu9L3ez3I7',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\v1\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing'=>false,
            'showScriptName' => false,
//            'suffix' => '.html',
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/book', 'v1/subscription', 'v1/reader',],
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v2/city', 'v2/post', 'v2/project', 'v2/resume', ],
                    'pluralize' => false,
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/candidate',
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{phone}' => '<phone:\\w+>',
                    ],
                    'extraPatterns' => [
                        'GET,HEAD {id}' => 'view',
                        'GET search/phone/{phone}' => 'phone',
                        'POST' => 'create',
                        'PUT {id}' => 'update',
                        'PATCH {id}' => 'update',
                    ],
                    'patterns' => [
                        'GET,HEAD' => 'index',
                        '' => 'options',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/stuff',
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                    'extraPatterns' => [
                        'GET,HEAD {id}' => 'view',
                        'POST login' => 'login',
                        'POST create' => 'create',
                        'PUT {id}' => 'update',
                        'PATCH {id}' => 'update',
                    ],
                    'patterns' => [
                        'GET,HEAD' => 'index',
                        '' => 'options',
                    ],
                ],
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
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
