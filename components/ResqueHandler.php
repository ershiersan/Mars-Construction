<?php
namespace app\components;

use Yii;

/**
 * @file components/ResqueHandler.php
 * @note 队列操作类
 * @date 2016-05-11
 */
class ResqueHandler {
    private static $key = 'default';
    private static $inited = false;

    /**
     * 初始化resque队列的配置
     * @$hostname redis库host
     * @$database redis库database，默认为8
     * @$password redis库password
     * @$key      resque队列key
     */
    public function init(
        $hostname=null,
        $database=null,
        $password=null,
        $key=null
    ) {
        if(self::$inited)
            return true;
        $hostname===null && $hostname = Yii::$app->redis->hostname;
        $database===null && $database = 8;
        $password===null && $password = Yii::$app->redis->password;
        $key!==null && self::$key = $key;
        $port = Yii::$app->redis->port;
        \Resque::setBackend('redis://'.(!empty($password)?":{$password}@":'')."{$hostname}:{$port}/".$database);
        self::$inited = true;
        return true;
    }

    /**
     * 插入EXEC队列
     * @$command exec脚本
     */
    public static function pushExec($command) {
        try{
            if(!self::init())
                return false;
            $args = array(
                'command' => $command,
                'time' => time(),
            );
            $jobId = \Resque::enqueue(self::$key, 'EXEC_JOB', $args, true);
            return $jobId;
        } catch (Exception $e) {
            return false;
        }
    }

}
