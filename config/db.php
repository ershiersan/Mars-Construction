<?php
//return [
//    'class' => 'yii\db\Connection',
//    'dsn' => 'mysql:host=127.0.0.1;dbname=ufs168_app',
//    'username' => 'root',
//    'password' => 'ilovechina',
//    'charset' => 'utf8mb4',
//];
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=172.20.6.100;dbname=biz_scrm',
    'username' => 'root', 
    'password' => '0Td1VuqklNm4O52',
    'charset' => 'utf8mb4',
    'tablePrefix' => 'pro_',
    'serverStatusCache' => 'FileCache', // 解决用从库循环引用的问题
    // 配置从服务器
    'slaveConfig' => [
        'username' => 'root',
        'password' => '0Td1VuqklNm4O52',
        'attributes' => [
            // use a smaller connection timeout
            PDO::ATTR_TIMEOUT => 10,
        ],
    ],

    // 配置从服务器组
    'slaves' => [
        [
            'dsn' => 'mysql:host=172.20.6.101;dbname=biz_scrm',
            'charset' => 'utf8mb4',
        ],
    ],
];
