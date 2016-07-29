<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\gii\generators\apiModule;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 *
 */
class Generator extends \yii\gii\Generator
{
    public $apiName;
    public $apiModuleDir = '@app/modules/api';

    //每一行的设置
    public $columnsOption = [];


    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'api模块 Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '在 @app/modules/api/ 中创建api目录.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['apiName'], 'required'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'apiName' => 'api名字',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'apiName' => 'api名字 eg: oauth',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes());
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = [
            new CodeFile(Yii::getAlias($this->apiModuleDir), ''),
        ];
        return $files;
    }
}
