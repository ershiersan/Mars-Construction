<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\base;
use Yii;

class CookieHelper
{
    public static function set($name, $value, $expire)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
        ]));
    }

    public static function get($name, $default = NULL)
    {
        $cookies = Yii::$app->request->cookies;
        return $cookies->getValue($name, $default);
    }

    public static function remove($name)
    {
        Yii::$app->response->cookies->remove($name);
    }
}
