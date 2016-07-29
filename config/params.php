<?php

$error_no_array = require(__DIR__ . '/error.cfg.php'); //使用同一配置 或是 使用本地配置
return [
    'scrm_api' => "http://c2.topchef.net.cn/",
    'php_dir' => '/home/pubsrv/php-5.5.18/bin/php',
    'error_no_array' => $error_no_array,
    'static_url'    => 'http://c.topchef.net.cn/onpack2/',
];
