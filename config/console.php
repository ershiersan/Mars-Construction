<?php
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'AdminCache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'Admin',
        ],
        'cache' => [
            'class' => 'yii\caching\DbCache',
            'keyPrefix' => 'app',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '172.20.6.101',
            'port' => 6379,
            'database' => 0,
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['zhyf'],
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'logVars' => [],
                    'logFile' => '@runtime/logs/addunionid.log',
                    'categories' => ['ufs\pro\addunionid*'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'logVars' => [],
                    'levels' => ['info'],
                    'categories' => ['zhyf', 'AssetManager'],
                ],
            ],
        ],
        'db' => $db,
    ],
    'params' => $params,
    'aliases' => [
        '@mdm/admin' => VENDOR_PATH . '/mdmsoft/yii2-admin/',
        '@sq' => VENDOR_PATH . '/../sqExt',
        '@yii/base' => VENDOR_PATH . '/../sqExt/base',
        '@phpbin' => '/home/pubsrv/php-5.5.18/bin/php',
    ]
];
