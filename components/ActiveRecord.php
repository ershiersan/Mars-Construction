<?php

namespace app\components;

use Yii;

class ActiveRecord extends \sq\base\ActiveRecord
{
    /**
     * 重写beforeSave，在保存的时候清空缓存
     * @param $index
     * @return array
     */
    public function beforeSave($insert) {
        // 保存会清理该模型的所有缓存，不太合理
        $key_cache = addslashes(self::getCacheKey());
        $arrKeys = Yii::$app->redis->keys("{$key_cache}*");
        if(is_array($arrKeys) && count($arrKeys)) {
            foreach($arrKeys as $cacheKey) {
                Yii::$app->redis->del($cacheKey);
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * 根据条件获取缓存key
     * @param $arrCondition
     * @return string
     */
    public static function getCacheKey($arrCondition=[]) {
        $keySuffix = '';
        if(is_array($arrCondition) && count($arrCondition) > 0) {
            ksort($arrCondition);
            foreach($arrCondition as $keyCondition => $condition) {
                $keySuffix .= ":{$keyCondition}_{$condition}";
            }
        }
        // return "ARCache:".self::tableName().':'.md5($keySuffix);
        return "ARCache:".self::tableName()."{$keySuffix}";
    }

    /**
     * 根据条件获取缓存数据
     * @param $arrCondition
     * @param $isOne true/false默认为all
     * @return array
     */
    public static function findFromCache($arrCondition, $isOne=false, $strOrder='') {
        if($isOne)
            $findCount = 'one';
        else
            $findCount = 'all';
        $key_cache = self::getCacheKey($arrCondition).":{$findCount}";
        $str_cached_msg = Yii::$app->redis->get($key_cache);
        if(empty($str_cached_msg)) {
            $arrResult = self::find()
                ->where($arrCondition);
            if($strOrder)
                $arrResult = $arrResult->orderBy($strOrder);
            #$arrResult = ->createCommand()->getRawSql();
            $arrResult = $arrResult->asArray()->$findCount();
            // die(print_r($arrResult, true));
            if(!empty($arrResult)) {
                Yii::$app->redis->set($key_cache, serialize($arrResult));
            }
        } else {
            $arrResult = unserialize($str_cached_msg);
        }
        return $arrResult;
    }
}
