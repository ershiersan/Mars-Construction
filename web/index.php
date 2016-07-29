<?php
// comment out the following two lines when deployed to production
ini_set('date.timezone','Asia/Shanghai');
if(file_exists(__DIR__ . '/../config-test')) {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_ENV') or define('YII_ENV', 'pro');
}

define('VENDOR_PATH', __DIR__ . '/../library/vendor');
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
error_reporting(0);
//error_reporting(E_ALL);
ini_set('display_errors', 'Off');//Off
require(VENDOR_PATH . '/autoload.php');
require(VENDOR_PATH . '/yiisoft/yii2/Yii.php');
require(VENDOR_PATH . '/../sqExt/base/JYii.php');

if(YII_ENV == 'dev') {
    $config = include(__DIR__ . '/../config-test/web.php');
} else {
    $config = include(__DIR__ . '/../config/web.php');
}

(new yii\web\Application($config))->run();

