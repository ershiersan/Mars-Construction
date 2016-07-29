<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_role_user".
 *
 * @property integer $userid
 * @property string $email
 * @property string $password
 * @property string $realname
 * @property string $jobtitle
 * @property string $icon
 * @property string $nickname
 * @property string $session
 * @property integer $biz_id
 * @property integer $ctime
 * @property integer $utime
 * @property integer $ltime
 * @property string $is_top
 * @property string $is_ban
 * @property string $is_del
 */
class AppRoleUser extends \sq\base\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_role_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['biz_id', 'ctime', 'utime', 'ltime'], 'integer'],
            [['is_top', 'is_ban', 'is_del'], 'string'],
            [['email', 'password', 'realname', 'jobtitle', 'icon', 'nickname', 'session'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userid' => '管理员id',
            'email' => '登录邮箱',
            'password' => 'Password',
            'realname' => 'Realname',
            'jobtitle' => '职位',
            'icon' => '用户头像',
            'nickname' => '昵称',
            'session' => '登录状态session',
            'biz_id' => '主帐号id',
            'ctime' => '创建时间',
            'utime' => '修改时间',
            'ltime' => '最后登录时间',
            'is_top' => '是否置顶：n未',
            'is_ban' => '是否被禁：n未禁',
            'is_del' => '是否删除：n未',
        ];
    }

    static public function attributeTypes() {
        return [
            'is_del' => [
                'y' => '是',
                'n' => '否',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['userid'=>$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByEmail($username)
    {
        return static::findOne(['email'=>$username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->userid;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->email === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }

}
