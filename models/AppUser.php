<?php

namespace app\models;

use Yii;

/**
 * @property integer $id
 */
class AppUser extends \yii\base\Object implements \yii\web\IdentityInterface
{


    public $biz_id;
    public $wx_id;
    public $pf_uid;
    public $pf_type;
    public $bi_minor_id = NULL;
    public $pmid;


    /**
     * @var AppUser
     */
    public static $user = NULL;

    public function validateSession($bi_expire_time) {
        if($this->pmid && $this->biz_id && $this->wx_id) {
            \Yii::$app->cache->set($this->pmid, $this, $bi_expire_time - time());
            self::$user = $this;

            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     * @return AppUser
     */
    public static function findIdentity($id)
    {
        if(self::$user && self::$user->id == $id) {
            return self::$user;
        }
        $user = \Yii::$app->cache->get($id);
        return $user ? $user : NULL;
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
        return $this->pmid;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->pmid;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->pmid === $authKey;
    }

    public function getIsMaster(){
        return $this->biz_id == $this->pmid;
    }
}
