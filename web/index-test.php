<?php
// NOTE: Make sure this file is not accessible when deployed to production
ini_set('date.timezone','Asia/Shanghai');
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//error_reporting(E_ALL);
ini_set('display_errors', 'On');

define('VENDOR_PATH', __DIR__ . '/../library/vendor');

require(VENDOR_PATH . '/autoload.php');
require(VENDOR_PATH . '/yiisoft/yii2/Yii.php');

if(file_exists(__DIR__ . '/../test')) {
    $config = include(__DIR__ . '/../test/web.php');
} else {
    $config = include(__DIR__ . '/../config/web.php');
}

(new yii\web\Application($config))->run();

