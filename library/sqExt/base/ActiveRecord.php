<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\base;

use Yii;

/**
 * 添加了  getAttributeByType()
 *         attributeTypes()
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    static public function attributeTypes() {
        return [];
    }

    static public function getAttributeByType($attribute, $type = '') {
        $attributeTypes = static::attributeTypes();
        if(isset($attributeTypes[$attribute])) {
            $types = $attributeTypes[$attribute];
            if($type !== '') {
                return isset($types[$type]) ? $types[$type] : '';
            } else {
                return $types;
            }
        }
        return [];
    }
}
