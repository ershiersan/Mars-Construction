<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_system_user".
 *
 * @property integer $uid
 * @property integer $platform_type
 * @property string $platform_uid
 * @property string $api_uid
 * @property string $pf_main_id
 * @property string $pf_minor_id
 * @property string $weixin_id
 * @property string $oauth_token
 * @property string $api_token
 * @property integer $expire_time
 * @property integer $oauth_expire
 * @property integer $api_expire
 * @property integer $creation_date
 * @property integer $status
 */
class AppSystemUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_system_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform_type', 'expire_time', 'oauth_expire', 'api_expire', 'creation_date', 'status'], 'integer'],
            [['api_uid', 'api_token', 'api_expire'], 'required'],
            [['platform_uid', 'api_uid', 'pf_main_id', 'pf_minor_id', 'weixin_id', 'oauth_token', 'api_token'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'uid' => 'Uid',
            'platform_type' => 'Platform Type',
            'platform_uid' => 'Platform Uid',
            'api_uid' => 'Api Uid',
            'pf_main_id' => 'Pf Main ID',
            'pf_minor_id' => 'Pf Minor ID',
            'weixin_id' => 'Weixin ID',
            'oauth_token' => 'Oauth Token',
            'api_token' => 'Api Token',
            'expire_time' => 'Expire Time',
            'oauth_expire' => 'Oauth Expire',
            'api_expire' => 'Api Expire',
            'creation_date' => 'Creation Date',
            'status' => 'Status',
        ];
    }

    /**
     * @inheritdoc
     * @return AppSystemUser
     */
    public static function findIdentity($id)
    {
        return static::findOne(['platform_uid'=>$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->platform_uid;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->platform_uid;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->platform_uid === $authKey;
    }
}
