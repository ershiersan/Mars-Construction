<?php
//header('Access-Control-Allow-Origin: *');

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'vendorPath' => VENDOR_PATH,
    'language'=>'zh-CN',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'onpack2' => [
            'class' => 'app\modules\onpack2\Module',
        ],
    ],
    'on beforeRequest' => function(){
        //解决url驼峰命名不能找到action的问题
//        $pathInfo = Yii::$app->request->getPathInfo();
//        $pathInfo = strtolower(preg_replace('/(\B[A-Z])/', '-$0', $pathInfo));
//
//        Yii::$app->request->setPathInfo($pathInfo);
    },
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '59MpdqvTy8ZPCZAcaTNZEGoq6Tpl5S5',
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\DbCache',
            'keyPrefix' => 'app',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
            'password' => 'ufsredis'
        ],
//        'session' => [
//            'class' => \yii\web\DbSession::className(),
//        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        // 'errorHandler' => [
        //     'errorAction' => 'site/error',
        // ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::className(),
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'htmlLayout' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.126.com',
                'username' => 'zhyf123456',
                'password' => 'zxcvbn',
                'port' => '25',
//                'encryption' => 'tls',

            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['zhyf123456@126.com'=>'admin']
            ],
            'on beforeSend' => function($message) {
                /* @var $message yii\mail\MailEvent */
                /* @var $mail yii\swiftmailer\Message */
                $mail = $message->message;
                $data = serialize($mail->getSwiftMessage());

                //保存状态，去另外一个地方发
                $key = md5(serialize($data));
                Yii::$app->cache->set($key, $data, 3600);
                $yiic =  Yii::getAlias('@app/yii');
                $command = "/home/pubsrv/php5.5/bin/php $yiic mail $key";
                `$command >dev/null 2>&1 &`; // fixme 这个地方需要配置路径
                return false;
            },
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['onpack2'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'logVars' => [],
                    'levels' => ['info'],
                    'categories' => ['onpack2', 'AssetManager', 'ufs*'],
                ],
            ],
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
//            'baseUrl' => 'http://7xj1rm.com1.z0.glb.clouddn.com/ufs168' . '/assets',//static url 链接
            'linkAssets' => true,
        ],
        'urlManager' => [
//            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                'admin/app-blacklist/index' => 'admin/log'
            ],
        ],
        'fileManager' => [
            'class' => 'app\components\FileManager',
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
    'aliases' => [
        '@mdm/admin' => VENDOR_PATH . '/mdmsoft/yii2-admin/',
//         '@mdm/admin' => '@app/modules/yii2-admin',
        '@sq' => VENDOR_PATH . '/../sqExt',
        '@yii/base' => VENDOR_PATH . '/../sqExt/base',
        // '@yii/zui' => '@app/sqExt/yii2-zui',
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] =
        [
           'class' => 'yii\debug\Module',
           'allowedIPs' => ['*', '127.0.0.1', '::1']
        ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] =[
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        'generators' => [
            'admincrud' => ['class' => 'sq\gii\generators\crud\Generator'],
            'sqmodel' => ['class' => 'sq\gii\generators\model\Generator'],
        ],
    ];
}

return $config;
