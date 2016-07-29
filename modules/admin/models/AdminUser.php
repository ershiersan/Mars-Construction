<?php

namespace app\modules\admin\models;
use Yii;

class AdminUser extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /* public $id;
    public $username;
    public $password; */
    public $authKey;
    public $accessToken;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            [['profile'], 'string'],
            [['email'], 'email'],
            [['username', 'password', 'salt', 'email'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'password' => '密码',
			'email' => '邮箱',
			'profile' => '备注',
		);
    }
	
	/**
	 * 保存前操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave($insert)
	{
		// 如果是新增记录
		if ($this->getIsNewRecord())
		{
			//生成随机10位字符
			$this->salt = $this->getStr();
			$this->password = $this->getPwd($this->password, $this->salt);
		} else {
			$oldModel = static::findOne([id=> $this->id]);
			if($oldModel->password != $this->password){
				$this->password = $this->getPwd($this->password, $this->salt);
			}
		}
		return parent::beforeSave($insert) && !$this->hasErrors();
	}
	
	public function getPwd($str, $salt){
		return md5($str.substr(md5($salt), 0, 8));
	}
	
	public function getStr(){
		$randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
		return substr($randStr,0,10);
	}

    private static $users = [
        '1111' => [
            'id' => '1111',
            'username' => 'admin',
            'password' => '321',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id'=>$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /* foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        } */
        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $this->getPwd($password, $this->salt);
    }
}
