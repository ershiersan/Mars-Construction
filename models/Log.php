<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 * @property string $is_del
 * @property integer $create_date
 */
class Log extends \sq\base\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['level', 'create_date'], 'integer'],
            [['log_time'], 'number'],
            [['prefix', 'message', 'is_del'], 'string'],
            [['create_date'], 'required'],
            [['category'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'level' => 'Level',
            'category' => 'Category',
            'log_time' => 'time',
            'prefix' => 'Prefix',
            'message' => 'Message',
            'is_del' => '是否删除 Y:是 N:否',
            'create_date' => '创建时间',
        ];
    }

    static public function attributeTypes() {
        return [
            'is_del' => [
                'Y' => '是',
                'N' => '否',
            ]
        ];
    }

    /**
    * 验证前操作
    */
    public function beforeValidate() {
        if ($this->getIsNewRecord()) {
            $this->is_del			= 'N';
            $this->create_date	= time();
        }
        return parent::beforeValidate();
    }
}
