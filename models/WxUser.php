<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "wx_user".
 *
 * @property integer $id
 * @property string $wx_open_id
 * @property string $user_tel
 * @property string $user_address
 * @property integer $subscribe_time
 * @property integer $unsubscribe_time
 * @property integer $wx_user_group_id
 * @property integer $wx_system_user_id
 * @property integer $wx_reply_context_id
 * @property string $remark_name
 * @property string $user_position
 * @property string $is_del
 * @property string $nickname
 * @property string $sex
 * @property string $remark_sex
 * @property integer $country
 * @property integer $province
 * @property integer $remark_province
 * @property integer $city
 * @property integer $remark_city
 * @property string $language
 * @property string $headimgurl
 * @property string $remark_headimgurl
 * @property string $image_id
 * @property integer $last_update_time
 * @property string $remark_desc
 * @property string $birthday
 * @property integer $send_num
 * @property integer $receive_num
 * @property integer $mass_msg_num
 * @property string $subscribe_source
 * @property integer $is_unilever
 * @property string $subscribe_qrcode_val
 * @property string $unionid
 * @property integer $update_info_time
 */
class WxUser extends \sq\base\ActiveRecord
{
    private $dbConnect = null;
    private function getDbConnect() {
        if($this->dbConnect == null) {
            $this->dbConnect = Yii::$app->db;
        }
        return $this->dbConnect;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wx_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'subscribe_time', 'unsubscribe_time', 'wx_user_group_id', 'wx_system_user_id', 'wx_reply_context_id', 'country', 'province', 'remark_province', 'city', 'remark_city', 'last_update_time', 'send_num', 'receive_num', 'mass_msg_num', 'is_unilever', 'update_info_time'
                ], 'integer'],
#            [['wx_system_user_id', 'wx_reply_context_id', 'image_id'], 'required'],
            [['is_del', 'sex', 'remark_sex', 'subscribe_source'], 'string'],
            [['wx_open_id', 'user_tel', 'user_position', 'unionid'], 'string', 'max' => 45],
            [['user_address', 'nickname', 'headimgurl', 'remark_headimgurl'], 'string', 'max' => 255],
            [['remark_name'], 'string', 'max' => 125],
            [['language'], 'string', 'max' => 64],
            [['image_id'], 'string', 'max' => 40],
            [['remark_desc'], 'string', 'max' => 2048],
            [['birthday'], 'string', 'max' => 120],
            [['subscribe_qrcode_val'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '对应联合利华的member_id',
            'wx_open_id' => '微信openid',
            'user_tel' => '用户手机号码',
            'user_address' => '用户录入地址',
            'subscribe_time' => '关注时间',
            'unsubscribe_time' => '取消关注时间',
            'wx_user_group_id' => '所属分组 默认值为0',
            'wx_system_user_id' => '所属商家id',
            'wx_reply_context_id' => '用户当前所在上下文位置  用户发消息来回复后记录下级节点id',
            'remark_name' => '备注名称',
            'user_position' => '用户所处的位置  数组下标字符串  php中eval',
            'wx_location' => '地理位置省市',
            'is_del' => 'N 未删除 Y 已删除',
            'nickname' => '用户昵称',
            'sex' => '性别 值为1时是男性，值为2时是女性，值为0时是未知',
            'remark_sex' => '备注 性别',
            'country' => '国家',
            'province' => '省份',
            'remark_province' => '备注省份',
            'city' => '城市',
            'remark_city' => '备注城市',
            'language' => '所用语言',
            'headimgurl' => '头像图片地址',
            'remark_headimgurl' => ' 备注头像',
            'image_id' => '七牛图片id',
            'last_update_time' => '最近一次对话时间',
            'remark_desc' => '备注',
            'birthday' => '生日',
            'send_num' => '发送给用户的消息条数',
            'receive_num' => '收到用户发的消息条数',
            'mass_msg_num' => '用户每月接收到的群发消息树 每月脚本清零',
            'subscribe_source' => '关注来源  二维码  qrcode',
            'is_unilever' => '是否从联合利华授权',
            'subscribe_qrcode_val' => '二维码关注值',
            'unionid' => 'unionid',
            'update_info_time' => '最后更新信息时间',
        ];
    }

    static public function attributeTypes() {
        return [
            'is_del' => [
                'Y' => '是',
                'N' => '否',
            ]
        ];
    }

    /**
    * 验证前操作
    */
    public function beforeValidate() {
        if ($this->getIsNewRecord()) {
            $this->is_del			= 'N';
            $this->creation_date	= time();
        }
        return parent::beforeValidate();
    }

    /**
     * 判断openid是否为广东地区的粉丝
     * a)     第一优先级：Golden Question中填写“广东”地区的
     * b)     第二优先级：LBS中定位为“广东”地区的
     * c)     第三优先级：注册微信时填写地位“广东”地区
     */
    public function isGuangdong() {
        $returnArray = array();
        if($this->isGqGuangdong()) {
            $returnArray[] = 1;
        }
        if($this->isLbsGuangdong()) {
            $returnArray[] = 2;
        }
        if($this->isWxGuangdong()) {
            $returnArray[] = 4;
        }
        return $returnArray;
    }

    /**
     * LBS中定位为“广东”地区的
     */
    public function isLbsGuangdong() {
        if(strpos($this->wx_location , '广东') !== false) 
            return true;
        else
            return false;
    }

    /**
     * Golden Question中填写“广东”地区的
     */
    public function isGqGuangdong() {
        $SQL = "SELECT answer from expiry_question where open_id='{$this->wx_open_id}' and pid=2 and is_del='N'";
        $connection = $this->getDbConnect(); //连接
        $command = $connection->createCommand($SQL);
        $result = $command->queryAll();
        if(is_array($result) && count($result) > 0) {
            foreach($result as $oneAnswer) {
                if(strpos($oneAnswer['answer'], '广东') !== false) {
                    // 答案当中包含广东关键字
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 注册微信时填写地位“广东”地区
     */
    public function isWxGuangdong() {
        return ($this->province == 10165/*广东省id*/);
    }

    /**
     * 根据id获取fields的值
     * $arrFields
     * $open_id
     */
    function getFieldsByOpenid($arrFields, $open_id) {
        $objWxUser = WxUser::findOne(['wx_open_id'=> $open_id]);
        $arrReturn = [];
        if($objWxUser) {
            foreach($arrFields as $oneField) {
                $arrReturn[$oneField] = $objWxUser->$oneField;
            }
        }
        return $arrReturn;
    }
}
