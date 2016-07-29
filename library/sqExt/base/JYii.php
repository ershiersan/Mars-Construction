<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

class JYii
{
    public static function runApi($route, $params = [], $clientId = '', $clientSrcret = '')
    {
        if ($clientId) {
            $timestamp = time();
            $signature = sha1("$clientId@$clientSrcret@$timestamp") . $timestamp;
            $request = Yii::$app->getRequest();
            $request->setQueryParams(['client_id' => $clientId, 'signature' => $signature]);
        }

        try {
            return json_decode(\Yii::$app->runAction($route, $params), true);
        } catch (\Exception $e ) {
            return self::endJson('500', $e->getMessage());
        }
    }

    public static function endJson($code, $msg = '', array $data = []) {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }
}
