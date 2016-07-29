<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pro_queue_monitor".
 *
 * @property integer $id
 * @property string $name
 * @property string $redis_host
 * @property string $redis_password
 * @property integer $redis_db
 */
class QueueMonitor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_queue_monitor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['redis_db'], 'integer'],
            [['name', 'redis_host', 'redis_password'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '队列名',
            'redis_host' => 'redis服务器地址',
            'redis_password' => 'redis服务器密码',
            'redis_db' => 'redis库名',
        ];
    }
}
