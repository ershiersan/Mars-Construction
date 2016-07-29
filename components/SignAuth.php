<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;
use app\modules\api\models\Activity;
use yii\filters\auth\AuthMethod;
use yii\helpers\VarDumper;

/**
 * QueryParamAuth is an action filter that supports the authentication based on the access token passed through a query parameter.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SignAuth extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'auth';
    public static $clientIdParam = 'client_id';
    public static $signatureParam = 'signature';
    public $except = [];

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {

        $clientId = $request->get(self::$clientIdParam) ? $request->get(self::$clientIdParam) : ($request->post(self::$clientIdParam) ? $request->post(self::$clientIdParam) : '');
        $signature = $request->get(self::$signatureParam) ? $request->get(self::$signatureParam) : ($request->post(self::$signatureParam) ? $request->post(self::$signatureParam) : '');
//        $clientId = '47855017';
//        $timestamp = time();
//        $signature = sha1("$clientId@5CdzNYCwiQFSYNi@$timestamp") . $timestamp;

        //兼容处理， 兼容老接口
        if(!$signature && ($request->pathInfo == "oauth/wechat"
            || $request->pathInfo == "home/get-sign-package"
            || $request->pathInfo == "card-p/get-cards"
            || $request->pathInfo == "card/get-cards")
        ) {


            $clientId = $request->get('client_id');
            $activity =  Activity::findOne(['client_id' => $clientId]);

            $clientSecret = $activity->client_secret;
            $timestamp = time();
            $signature = sha1("$clientId@$clientSecret@$timestamp") . $timestamp;
        }
        $identity = $user->loginByAccessToken([$clientId, $signature], get_class($this));
        if ($identity !== null) {
            return $identity;
        }
        return null;
    }

    public function beforeAction($action) {
        if(in_array($action->id, $this->except)) {
            return true;
        }
        return parent::beforeAction($action);
    }
}
