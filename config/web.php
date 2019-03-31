<?php


$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => getenv('APP_NAME'),
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'app\bootstrap\SetUp',
    ],
    'language'=> getenv('APP_LANG'),
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@upload' => sprintf('@app/web/%s/', getenv('UPLOAD_IMAGES_FOLDER')),
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PgHstGPH6xENmHQJ494Vic7gdk1yJooF',
			'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'elisdn\hybrid\AuthManager',
            'modelClass' => 'app\models\User',
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                'action' => yii\web\UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                '' => 'post/index',
                'post/<id:\d+>' => 'post/view',
                'category/<id:\d+>' => 'post/category',
                '<action:(login|logout|signup)>'=>'auth/<action>',
                '<module:(client|admin)>/<controller:(post)>/<action:(view|update|delete)>/<id:\d+>' => '<module>/<controller>/<action>',

                //api-v1
                ['class' => 'yii\rest\UrlRule', 'prefix' => 'api', 'controller' => ['v1/category']],
                ['class' => 'yii\rest\UrlRule',
                    'prefix' => 'api',
                    'controller' => ['v1/user'],
                    'tokens' => [
                        '{id}' => '<id:\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/ban' => 'ban',
                        'GET {id}/unban' => 'unban',
                    ]
                ],
                ['class' => 'yii\rest\UrlRule',
                    'prefix' => 'api',
                    'controller' => ['v1/post'],
                    'tokens' => [
                        '{id}' => '<id:\d+>',
                    ],
                    'extraPatterns' => [
                        'GET {id}/activate' => 'activate',
                    ]
                ],
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'api/v1',
                    'routePrefix' => 'v1',
                    'rules' => [
                        'auth' => 'auth/index',
                        'GET profile' => 'profile/index',
                        'PUT profile' => 'profile/update',
                    ]
                ],

                //api-v2
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'api/v2',
                    'routePrefix' => 'v2',
                    'rules' => [
                        'POST auth' => 'auth/index',
                        'GET profile' => 'profile/index',
                        'PUT profile' => 'profile/update',

                        'GET posts' => 'post/index',
                        'GET posts/<id:\d+>' => 'post/view',
                        'GET posts/<id:\d+>/activate' => 'post/activate',
                        'POST posts' => 'post/create',
                        'PUT posts/<id:\d+>' => 'post/update',
                        'DELETE posts/<id:\d+>' => 'post/delete',

                        'GET categories' => 'category/index',
                        'GET categories/<id:\d+>' => 'category/view',
                        'POST categories' => 'category/create',
                        'PUT categories/<id:\d+>' => 'category/update',
                        'DELETE categories/<id:\d+>' => 'category/delete',

                        'GET users' => 'user/index',
                        'GET users/<id:\d+>' => 'user/view',
                        'GET users/<id:\d+>/<action:(ban|unban)>' => 'user/<action>',
                        'POST users' => 'user/create',
                        'PUT users/<id:\d+>' => 'user/update',
                        'DELETE users/<id:\d+>' => 'user/delete',
                    ]
                ],
            ],
        ],
    ],

    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'client' => [
            'class' => 'app\modules\client\Module',
        ],
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
        'v2' => [
            'class' => 'app\modules\v2\Module',
        ],
        'debug' => [
            'class' => 'yii\debug\Module',
        ]
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

        // for vagrant
        'allowedIPs' => ['127.0.0.1', '::1', '172.19.0.1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '172.19.0.1'],
    ];
}

return $config;
