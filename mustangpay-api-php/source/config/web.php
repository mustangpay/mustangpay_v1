<?php
$prefix  = 'x-sure';
$rootPath = dirname(dirname(__DIR__));
$params = array_merge(
    require __DIR__ . '/params.php'
);
$db = require __DIR__ . '/db.php';
$urlRules = require __DIR__ . '/url.php';
$config = [
    'id' => $prefix,
    'name' => $prefix . '-cms',
    'vendorPath' => $rootPath . '/system/vendor',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',

    //中->英
    'sourceLanguage' => 'zh-CN',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@root' => $rootPath,
    ],
    'modules' => [
        'document' => [
            'class' => 'ibunao\apidoc\Module',
            # 配置访问接口的host  通常配置 frontend 项目的域名
            'debugHost' => 'http://nanfeiapi/',
            # 和配置时定义的模块名一致
            'moduleName' => 'document',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mxGhoSAypxH-yA0ZWpBmvTsIhNs8fyB2',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m-d') . '.log',
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true, //开启 url 美化
            'showScriptName' => false, //是否显示 index.php
            // 严格解析
            // 'enableStrictParsing' => false,
            //'suffix' => '.html',
            'rules' => $urlRules,
        ],
    ],
    'params' => $params,
    'controllerMap' => [
        'file' => [
            'class' => 'app\common\FileBaseController',
        ],
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
         'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1' , '::1'],
    ];
}
return $config;
