<?php
namespace app\components\wechat;
/**
 * 微信 SDK API 操作类
 *
 * 微信公众平台文档：{@link http://mp.weixin.qq.com/wiki/index.php?title=%E6%B6%88%E6%81%AF%E6%8E%A5%E5%8F%A3%E6%8C%87%E5%8D%97}
 *
 * @package wechat
 * @author Wanming Gao
 * @version 2.0
 * @updated  2013/08/07 兼容微信5.0 自定义菜单功能
 */
class WechatSDK {

    /**
     * @token    用户自定义token 由品趣生成 用户填在微信公众网站
     * 
     */
    private $token;



    /**
     * @appid    第三方用户唯一凭证 由客户填写至品趣系统
     * 
     */
    private $appid;



    /**
     * @secret    第三方用户唯一凭证密钥，既appsecret
     * 
     */
    private $secret;



    /**
     * @access_token    取凭证接口获取到access_token
     * 
     */
    private $access_token;



    /**
     * @expires_time    access_token 的有效期
     * 
     */
    private $expires_time;



    /**
     * @uid    system_user.id  wx_system_user.uid 品趣用户id
     * 
     */
    private $uid;
    
    /**
     * 秘钥
     * @var [type]
     */
    private $encodingAesKey;

    /**
     * 微信唯一ID
     * @var [type]
     */
    private $wx_system_user_id;
	  /**
     * @uploadimg_url  上传图片url
     * access_token 需传入
     *
     */
    private $uploadimg_url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=%s";


    /**
     * @access_token_url  获取凭证access_token 的url
     * grant_type=  默认为client_credential  appid=%s secret=%s 需要传入
     *
     */
    private $access_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';

	/**
     * @getticket_url  获取jssdk
     * access_token 需传入
     *
     */
	 private $getticket_url    = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=%s";
    /**
     * @menu_create_url  创建自定义菜单url
     * access_token 需传入
     *
     */
    private $menu_create_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";


    /**
     * @menu_get_url  查询当前使用的自定义菜单结构 url
     * access_token 需传入
     *
     */
    private $menu_view_url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s";


    /**
     * @menu_delete_url  取消当前使用的自定义菜单 url
     * access_token 需传入
     *
     */
    private $menu_delete_url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s";


    /**
     * @user_list_url  获取关注者列表 url
     * access_token 需传入
     * next_openid  
     */
    private $user_list_url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s";


    /**
     * @user_info_url  获取用户基本信息 url
     * access_token 需传入
     * openid  
     */
    private $user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s";

    /**
     * @user_group_url  获取用户分组 url
     * 
     */
    private $user_group_url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token=%s";

    /**
     * 创建分组
     * @var string
     */
    private $user_group_create_url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token=%s";

    /**
     * 编辑组名称
     * @var string
     */
    private $user_group_edit_url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token=%s";

    /**
     * 查看用户的分组信息
     * @var string
     */
    private $user_group_get_url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=%s";

    /**
     * 移动用户分组
     * @var string
     */
    private $user_group_move_url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=%s";

    /**
     * @custom_message_url  发送客服消息 url
     * access_token 需传入  
     */
    private $custom_message_url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s";

    /**
     * @media_get_url  多媒体文件下载 url(暂不支持视频文件)
     * access_token
     * 
     */
    private $media_get_url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s";

    /**
     * @media_upload_url  多媒体文件上传 url
     * access_token
     * 
     */
    private $media_upload_url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s";

    /**
     * @media_upload_url  上传永久素材 url
     * access_token
     *
     */
    private $media_permanent_upload_url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=%s";
    
    /**
     * @del_media_permanent_url  删除永久素材
     * access_token
     */
    private $del_media_permanent_url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=%s";
    
    
    /**
     * @news_upload_url  图文消息素材上传 url
     * access_token
     * 
     */
    private $news_upload_url = "https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=%s";
   
    
    
    
    
    /**
     * @message_send_url  根据OpenID列表群发 url
     * access_token
     * 
     */
    private $message_send_url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=%s";

    /**
     * @message_sendall_url  根据分组进行群发 url
     * access_token
     * 
     */
    private $message_sendall_url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=%s";

    /**
     * @message_send_preview_url 群发预览接口
     * @var string
     */
    private $message_send_preview_url = "https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=%s";

    /**
     * @message_delete_url   删除群发 url
     * access_token
     * 
     */
    private $message_delete_url = "https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=%s";

    /**
     * @create_shorturl_url  将一条长链接转成短链接  url
     * access_token
     * 
     */
    private $create_shorturl_url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=%s";
    /**
     * 生成二维码
     * @var string
     */
    private $qrcode_create_url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s";


    /**
     * 群发图文统计
     * @var string
     */
    private $news_total_stat_url = "https://api.weixin.qq.com/datacube/getarticletotal?access_token=%s";

    /**
     * 用户统计
     * @var string
     */
    private $user_summary_url = "https://api.weixin.qq.com/datacube/getusersummary?access_token=%s";

	/**
     * 页面授权地址
     * @var string
	 * @author chenhao
     */
    private $oauth_redirect_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect";
    
	private $material_num_url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=%s";
	private $batchget_material_url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=%s";
	private $get_material_url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=%s";
	private $add_news_url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=%s";
	private $update_news_url = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=%s";
	private $del_material_url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=%s";
    /**
     * 页面授权获取oauth_access_token地址
     * @var string
	 * @author chenhao
     */
    private $oauth_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code";
	
	/**
     * 页面授权获取用户信息地址
     * @var string
	 * @author chenhao
     */
    private $oauth_userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s";

	/**
	 *@note 查询群发消息发送状态
	 *@date 2015/7/28 handandan
	 */
	private $search_mass_status_url = "https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token=%s";
    /**
     * 构造函数
     *
     * @access public
     * @param  array $wx_sys_user   格式规则与数据库查询一维数组相同  减少查询请求 待优化
     * @param  none
     * @return void
     */
    function __construct($wx_sys_user)
    {
        //$wx_sys_user = wx_system_user::getUserInfo($uid);
        if ($wx_sys_user && !empty($wx_sys_user)) {
            if(isset($wx_sys_user['wx_custom_token'])) {
                $this->token = $wx_sys_user['wx_custom_token'];//用户token
            }else {
                $this->token = '';
            }

            if(isset($wx_sys_user['wx_appid'])) {
                $this->appid = $wx_sys_user['wx_appid'];//用户token
            }else {
                $this->appid = '';
            }

            if(isset($wx_sys_user['wx_appsecret'])) {
                $this->secret = $wx_sys_user['wx_appsecret'];//用户token
            }else {
                $this->secret = '';
            }

            if (isset($wx_sys_user['wx_access_token'])) {
                $this->access_token = $wx_sys_user['wx_access_token'];//用户token
            }else {
                $this->access_token = '';
            }

            if (isset($wx_sys_user['expires_time'])) {
                $this->expires_time = $wx_sys_user['expires_time'];//用户token
            }else {
                $this->expires_time = '';
            }

            if (isset($wx_sys_user['uid'])) {
                $this->uid = $wx_sys_user['uid'];//品趣用户id
            }else {
                $this->uid = '';
            }

            //zaki 2015-01-06 秘钥
            if(isset($wx_sys_user['wx_encodingaeskey'])){
                $this->encodingAesKey = $wx_sys_user['wx_encodingaeskey'];
            }else {
                $this->encodingAesKey = '';
            }

            if(isset($wx_sys_user['id']) && !empty($wx_sys_user['id'])){
                $this->wx_system_user_id =  $wx_sys_user['id'];
            }else {
                $this->wx_system_user_id = 0;
            }
        }
        
    }

    /**
     * setAccessToken
     * 2013-12-20 Shangzhetong
     * sdk实例化后为动态设置AccessToken使用
     * 为tools/wx_fetch_userlist.php中使用添加
     * @param [type] $token [description]
     */
    public function setAccessToken($token) 
    {  
        $this->access_token = $token;
    }

    /**
     * setExpiresTime
     * 2013-12-20 Shangzhetong
     * sdk实例化后为动态设置ExpiresTime使用
     * @param [type] $time [description]
     */
    public function setExpiresTime($time) 
    {  
        $this->expires_time = $time;
    }

    /**
     * 回复文本函数
     *
     * @access public
     * @param  $toUsername    openid
     * @param  $keyword       string
     * @param  $fromUsername  开发者微信号
     * @return 
     */
    public function responseTextMsg($toUsername, $keyword, $fromUsername)
    {
        $time = time();
        $textTpl = <<<EOF
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>
EOF;
        if(!empty( $keyword ))
        {
            $resultStr = sprintf($textTpl, $toUsername, $fromUsername, $time, $keyword);
            return $resultStr;
        }else{
            return false;
        }

    }

    /**
     * 响应图片
     * @param  [type]     $toUsername   [description]
     * @param  [type]     $MediaId      [description]
     * @param  [type]     $fromUsername [description]
     * @return [type]                   [description]
     *
     * @author  zaki
     * @date   2015-01-13
     */
    public function responseImageMsg($toUsername, $MediaId, $fromUsername){
        $time = time();
        $textTpl = <<<EOF
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%s]]></MediaId>
</Image>
</xml>
EOF;
        if(!empty( $MediaId ))
        {
            $resultStr = sprintf($textTpl, $toUsername, $fromUsername, $time, $MediaId);
            return $resultStr;
        }else{
            return false;
        }

    }

    /**
     * 响应语音
     * @param  [type]     $toUsername   [description]
     * @param  [type]     $MediaId      [description]
     * @param  [type]     $fromUsername [description]
     * @return [type]                   [description]
     *
     * @author  zaki
     * @date   2015-01-13
     */
    public function responseVoiceMsg($toUsername, $MediaId, $fromUsername){
        $time = time();
        $textTpl = <<<EOF
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
<Voice>
<MediaId><![CDATA[%s]]></MediaId>
</Voice>
</xml>
EOF;
        if(!empty( $MediaId ))
        {
            $resultStr = sprintf($textTpl, $toUsername, $fromUsername, $time, $MediaId);
            return $resultStr;
        }else{
            return false;
        }

    }
    /**
     * 回复文本函数
     *
     * @access public
     * @param  $toUsername    openid
     * @param  $arrNews       array 二维数组  
     * @param  $fromUsername  开发者微信号
     * @param  $imgurl_ident  图片是否是链接  0为id，1为链接
     * @return 
     */
    /*
     图文信息数组示例  必须为以下格式
     array(
       array(
           'Title'        => '图文消息标题',
           'Description'  => '图文消息描述',
           'PicUrl'       => '图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80。',
           'Url'          => '点击图文消息跳转链接'
        ),
        array(
           'Title'        => '图文消息标题',
           'Description'  => '图文消息描述',
           'PicUrl'       => '图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80。',
           'Url'          => '点击图文消息跳转链接'
        ),
        array(
           'Title'        => '图文消息标题',
           'Description'  => '图文消息描述',
           'PicUrl'       => '图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80。',
           'Url'          => '点击图文消息跳转链接'
        ),
        array(
           'Title'        => '图文消息标题',
           'Description'  => '图文消息描述',
           'PicUrl'       => '图片链接，支持JPG、PNG格式，较好的效果为大图640*320，小图80*80。',
           'Url'          => '点击图文消息跳转链接'
        )
        .
        .
        .
     )


    */
    public function responseNewsMsg($toUsername, $arrNews, $fromUsername,$imgurl_ident = 0)
    {
        $time = time();
        $articlesTpl = <<<EOF
<item>
<Title><![CDATA[%s]]></Title> 
<Description><![CDATA[%s]]></Description>
%s
<Url><![CDATA[%s]]></Url>
</item>
EOF;
         $articlesStr = '';
         foreach ($arrNews as $k => $news) {
            if(empty($imgurl_ident)) {
                 $news['PicUrl'] ? $img_url = lib_base::image_url($news['PicUrl'], 0, 0) : $img_url = '';
            }else {
                $news['PicUrl'] ? $img_url = $news['PicUrl'] : $img_url = '';
            }
            if($news['Url']){
                $news['Url'] = htmlspecialchars_decode($news['Url']);
                if(strpos($news['Url'],'?')){
                    $news['Url'] .= "&open_id=".$toUsername;
                }
                else{
                    $news['Url'] .= "?open_id=".$toUsername;
                }
            }
            if($img_url){//如果有图片的话增加封面的xml
                $img_url = sprintf("<PicUrl><![CDATA[%s]]></PicUrl>",$img_url);
            }
             $articlesStr .= sprintf($articlesTpl, $news['Title'], $news['Description'], $img_url, $news['Url']);
         }
         $cnt = count($arrNews);
        $msgTpl =  <<<EOF
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
#articlesStr#
</Articles>
<FuncFlag>0</FuncFlag>
</xml>
EOF;
        $resultStr = sprintf($msgTpl, $toUsername, $fromUsername, $time, $cnt);
        // 2013-10-21 shangzhetong 
        // 修正连接被urlencode的参数不能解析的bug
        $resultStr = str_replace('#articlesStr#', $articlesStr, $resultStr);
        return $resultStr;
    }
    
    /**
     * 效验请求
     * 公众平台用户提交信息后，微信服务器将发送GET请求到填写的URL上
     * @access public
     * @param  signature   微信加密签名
     * @param  timestamp   时间戳
     * @param  nonce       随机数
     * @return void
     */
    public function checkSignature($signature, $timestamp, $nonce)
    {
        Lib_Zdebug::tofile($nonce, 'nonce');
        $token = $this->token;
        //error_log(print_r('local_token:'.$token."\r\n", true), 3, '/tmp/pinqu_wechat.log');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        //error_log(print_r('local_signature:'.$tmpStr."\r\n", true), 3, '/tmp/pinqu_wechat.log');
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 格式化accesstoken 获取 url
     * @access public
     * @return string  url
     */
    public function getAccessTokenUrl(){
        if (empty($this->appid) || empty($this->secret)) {
            return false;
        }
        Lib_Yiilog::info($this->appid, 'ufs\api\accesstoken\getAccessTokenUrl');
        return sprintf($this->access_token_url, $this->appid, $this->secret);
    }

    /**
     * 获取access_token以及时间
     * @return array
     */
    public function getAccessToken($url) {
        if(empty($url)) {
            return false;
        }
        return $this->sendRequest($url, 'GET');
    }

    /**
     * 格式化menu_create_url 获取 url
     * @access public
     * @return string  url
     */
    public function getMenuCreateUrl(){
        if (empty($this->access_token)) {
            return false;
        }
        return sprintf($this->menu_create_url, $this->access_token);
    }

    /**
     * 格式化menu_view_url 获取 url
     * @access public
     * @return string  url
     */
    public function getMenuViewUrl(){
        if (empty($this->access_token)) {
            return false;
        }
        return sprintf($this->menu_view_url, $this->access_token);
    }

    /**
     * 格式化menu_delete_url 获取 url
     * @access public
     * @return string  url
     */
    public function getMenuDeleteUrl(){
        if (empty($this->access_token)) {
            return false;
        }
        return sprintf($this->menu_delete_url, $this->access_token);
    }

    /**
     * 微信消息加密函数
     * @param  [type] $text       正常输出xml数据
     * @param  [type] $timeStamp  随机时间 微信传来的
     * @param  [type] $nonce      随机串 微信传来的
     * @return bool or string
     */
    public function encryptMsg($text, $timeStamp, $nonce){
        include_once "wxBizMsgCrypt.php";
        $pc = new WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appid);
        $errCode = $pc->encryptMsg($text, $timeStamp, $nonce, $encryptMsg);
        if ($errCode == 0) {
            return $encryptMsg;
        }else{
            return false;
        }
    }

    /**
     * 消息解密函数
     * @param  [type] $msg_sign  加密串  微信传过来的
     * @param  [type] $timeStamp 随机时间 微信传来的
     * @param  [type] $nonce     随机串 微信传来的
     * @param  [type] $from_xml  [description]
     * @return [type]            [description]
     */
    public function decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml){
        include_once "wxBizMsgCrypt.php";
        $pc = new WXBizMsgCrypt($this->token, $this->encodingAesKey, $this->appid);
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if ($errCode == 0) {
            return $msg;
        }else{
            return false;
        }
    }

    /**
     * 获取帐号的关注者列表
     * @access public
     * @param  $next_openid  第一个拉取的OPENID，不填默认从头开始拉取
     * @return string  url
     */
    public function getUserList($next_openid = NULL){
        if (empty($this->access_token)) {
            return false;
        }
        $user_list_url = $this->user_list_url;
        if ($next_openid) {
            $user_list_url .= '&next_openid='. $next_openid;
        }
        $user_list_url = sprintf($user_list_url, $this->access_token);
        return $this->sendRequest($user_list_url, 'GET');
    }

    /**
     * @note 获取用户的分组名列表
     * @return array
     *
     */
    public function getGroupList() {
        $group_url = sprintf($this->user_group_url, $this->access_token);
        return $this->sendRequest($group_url, 'GET');
    }

    /**
     * 获取用户基本信息
     * @access public
     * @param  $openid  普通用户的标识，对当前公众号唯一
     * @return string  url
     */
    public function getUserInfo($openid){
        if (empty($this->access_token) || empty($openid)) {
            return false;
        }
        $user_info_url = sprintf($this->user_info_url, $this->access_token, $openid);
        return $this->sendRequest($user_info_url, 'GET');
    }

    /**
     * @note   微信长链接转短连接
     * @param  string $long_url [description]
     * @return [type]           [description]
     */
    public function getShortUrl($long_url) {
        if(empty($long_url)) {
            return false;
        }
        $create_shorturl = sprintf($this->create_shorturl_url, $this->access_token);
        $params = self::json_encode(array('action'=> 'long2short', 'long_url'=> $long_url));
        return $this->sendRequest($create_shorturl, 'POST', $params);
    }

    /**
     * 发送客服消息
     * @access public
     * @param  $msg_json  json格式消息 支持文本|图片|语音|视频|音乐|图文
     * @return string  返回结果
     */
    public function sendCustomMessage($msg_json){
        if (empty($this->access_token) || empty($msg_json)) {
            return false;
        }
        $custom_message_url = sprintf($this->custom_message_url, $this->access_token);
        return $this->sendRequest($custom_message_url, 'POST', $msg_json);
    }

    /**
     * 创建微信高级接口 json信息
     * @param  string $type   text image voice video music news 
     * @param  string $toUser  openID
     * @param  array $Content 需要发送的参数数组  注意内容 urlencode一下  要不中文乱码
     * @return json   
     *
     * http://mp.weixin.qq.com/wiki/index.php?title=%E5%8F%91%E9%80%81%E5%AE%A2%E6%9C%8D%E6%B6%88%E6%81%AF#.E5.8F.91.E9.80.81.E6.96.87.E6.9C.AC.E6.B6.88.E6.81.AF
     */
    public function createJson($type,$toUser,$Content){
        $array = array(
                    "touser"=>$toUser,
                    "msgtype"=>$type,
                    $type=>$Content
            );

         return self::json_encode($array);
    }

    /**
     * 微信api不支持中文转义的json结构
     * @param array $arr
     */
    function json_encode($arr) {
        $parts = array ();
        $is_list = false;
        //Find out if the given array is a numerical array
        $keys = array_keys ( $arr );
        $max_length = count ( $arr ) - 1;
        if (($keys [0] === 0) && ($keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1
            $is_list = true;
            for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position
                if ($i != $keys [$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) { //Custom handling for arrays
                if ($is_list)
                    $parts [] = self::json_encode ( $value ); /* :RECURSION: */
                else
                    $parts [] = '"' . $key . '":' . self::json_encode ( $value ); /* :RECURSION: */
            } else {
                $str = '';
                if (! $is_list)
                    $str = '"' . $key . '":';
                //Custom handling for multiple data types
                if (is_numeric ( $value ) && $value<2000000000)
                    $str .= '"'.$value.'"'; //Numbers
                elseif ($value === false)
                $str .= 'false'; //The booleans
                elseif ($value === true)
                $str .= 'true';
                else
                    $str .= '"' . addslashes ( $value ) . '"'; //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
                $parts [] = $str;
            }
        }
        $json = implode ( ',', $parts );
        if ($is_list)
            return '[' . $json . ']'; //Return numerical JSON
        return '{' . $json . '}'; //Return associative JSON
    }
    
    /**
     * Format and sign an API request
     * @param  $url     api url
     * @param  
     * @return string
     * @ignore
     */
    public function sendRequest($url, $method, $parameters = array(),$type = '') {
      
        switch ($method) {
            case 'GET':
                //$url = $url . '?' . http_build_query($parameters);
                return $this->http($url, 'GET');
                break;
            default:
                if (is_array($parameters)) {
                    $body = $parameters;
                } else {
                    //json
                    $body = $parameters;
                }
                return $this->http($url, $method, $body,$type);
                break;
        }
    }


    /**
     * Make an HTTP request
     *
     * @return string API results
     * @ignore
     */
    public function http($url, $method, $postfields = NULL,$type = '') {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        // 2013-12-19 Shangzhetong
        $HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $HTTP_USER_AGENT);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 30);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);
      
        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
        }
        curl_setopt($ci, CURLOPT_URL, $url );
        
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
        //file_put_contents('/home/sqzhangyujie/test/log.txt', $method."111111333333tyhjjjnbbbb\r\n",FILE_APPEND);
        
        $response = curl_exec($ci);
       // file_put_contents('/home/sqzhangyujie/test/log.txt', print_r($response,true)."111111333333tyhjjjnbbbb\r\n",FILE_APPEND);
        
        curl_close ($ci);
		if($type == 'file'){
			return $response;
		}else{
			return json_decode($response, true);
		}
        
    }

    /**
     * 请求指定的url地址(抄自$this->http方法，返回curl请求到的原始数据)
     * @author Shangzhetong
     * @param  [type] $url        [description]
     * @param  [type] $method     [description]
     * @param  [type] $postfields [description]
     * @param  array  $header     [description]
     * @return [type]             [description]
     */
    public function requestHttp($url, $method, $postfields = NULL, $header = array()) {
        $this->http_info = array();
        $ci = curl_init();
        /* Curl settings */
        $HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        // 2014/07/14 Shangzhetong (修正第三方平台不响应请求的问题)
        // 指定Content-Type: text/xml;头解决第三方问题
        if ($header) curl_setopt($ci, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, $HTTP_USER_AGENT);
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ci, CURLOPT_TIMEOUT, 10);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ci, CURLOPT_ENCODING, "");
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_HEADER, FALSE);

        switch ($method) {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($postfields)) {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
        }
        curl_setopt($ci, CURLOPT_URL, $url );
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );

        $response = curl_exec($ci);
        curl_close ($ci);
        return $response;
    }
    
    /**
     * 下载媒体资源上传到7牛
     * @access public
     * @param  $media_id   微信媒体id 暂不支持视频
     * @param  $media_mime 媒体文件mime类型 Format
     * @return string      媒体下载地址
     */
    public function downloadMediaToQbox($media_id, $media_mime = 'amr'){
        // $media_id = 'CRacDNh0A6FlXVyoAEhIXwzYpXQGEVKU9mWuoW2xVUEDaQdByqP8-qqAL61yXI6Z';
        if (empty($this->access_token) || empty($media_id)) {
            return false;
        }
        $media_get_url = sprintf($this->media_get_url, $this->access_token, $media_id);
        return $media_get_url;
    }

    /**
     * 上传媒体资源到微信
     * @access public
     * @param  $media  媒体文件
     * @param  $type   媒体文件mime类型 Format  image|voice|video|thumb
     * @return string      json
     */
    public function uploadMediaToWx($media, $type = 'image'){
        // $media_id = 'CRacDNh0A6FlXVyoAEhIXwzYpXQGEVKU9mWuoW2xVUEDaQdByqP8-qqAL61yXI6Z';
        if (empty($this->access_token) || empty($media)) {
            return false;
        }
        $param['media'] = '@'.$media;
        $media_post_url = sprintf($this->media_upload_url, $this->access_token, $type);

        return $this->sendRequest($media_post_url, 'POST', $param);
    }


    /**
     * 上传图文消息素材
     * @access public
     * @param  $articles_json  json格式图文消息素材
     * @return string  返回结果
     */
    public function uploadNews($articles_json){
        if (empty($this->access_token) || empty($articles_json)) {
            return false;
        }
        $news_upload_url = sprintf($this->news_upload_url, $this->access_token);
        return $this->sendRequest($news_upload_url, 'POST', $articles_json);
    }

    /**
     * 群发消息
     * @access public
     * @param  $articles_json  json格式图文消息素材
     * @param  $sd_type  int 1 by open_id 2 by group id 默认 1
     * @return string  返回结果
     */
    public function sendMassMessage($message_json, $sd_type = 1){
        if (empty($this->access_token) || empty($message_json)) {
            return false;
        }

        $message_send_url = $sd_type == 1 ? $this->message_send_url : $this->message_sendall_url;
        $message_send_url = sprintf($message_send_url, $this->access_token);
        return $this->sendRequest($message_send_url, 'POST', $message_json);
    }

    /**
     * 群发预览
     * @access public
     * @param  $articles_json  json格式图文消息素材
     * @param  $sd_type  int 1 by open_id 2 by group id 默认 1
     * @return string  返回结果
     */
    public function sendMassPreviewMessage($message_json){
        if (empty($this->access_token) || empty($message_json)) {
            return false;
        }
        $message_send_url = sprintf($this->message_send_preview_url, $this->access_token);
        return $this->sendRequest($message_send_url, 'POST', $message_json);
    }

    /**
     * 删除群发
     * @access public
     * @param  $post_json  json格式参数
     * @return string  返回结果
     */
    public function delMassMessage($post_json){
        if (empty($this->access_token) || empty($post_json)) {
            return false;
        }

        $message_delete_url = sprintf($this->message_delete_url, $this->access_token);
        return $this->sendRequest($message_delete_url, 'POST', $post_json);
    }

    /**
     * 生成二维码
     * @param  [type] $scene_id       唯一码
     * @param  string $action_name    默认临时二维码 QR_SCENE  永久二维码 QR_LIMIT_SCENE
     * @param  string $expire_seconds [description]
     * @return [type]                 [description]
     */
    public function createQrcode($scene_id,$action_name="QR_SCENE",$expire_seconds="1800"){
        if (empty($this->access_token) || empty($scene_id)) {
            return false;
        }
        if($action_name != 'QR_SCENE' && ($scene_id < 0 || $scene_id > 100000) ){
            return false;
        }
        if($action_name == 'QR_SCENE' && ($expire_seconds < 0 || $expire_seconds > 1800)){
            return false;
        }
        $array = array(
            "action_name"=>$action_name,
            "action_info"=>array("scene"=>array("scene_id"=>$scene_id))
        );
        if($action_name == 'QR_SCENE'){
            $array["expire_seconds"] = $expire_seconds;
        }
        $qrcode_create_url = sprintf($this->qrcode_create_url, $this->access_token);
        return $this->sendRequest($qrcode_create_url, 'POST', json_encode($array));
    }

    /**
     * 微信多媒体资源和七牛综合文件上传。
     * 
     * @access public
     * @param  $upfile  array 要上传的文件 $_FILES 接收的值
     * @param  $type   string 资源文件类型（缺省：image）
     * @return json  返回结果
     */
    public function uploadMediaToWxAndQn($upfile, $type = 'image'){
        if(empty($this->wx_system_user_id)){
            return json_encode(array('errcode' => '00009', 'errmsg' => '缺少公众号唯一ID'));
        }
        $name=$upfile["name"];
        //$filetype=$upfile["type"];
        $suffix = end(explode('.', $name));
        $size=$upfile["size"];
        $myfile=$upfile["tmp_name"];
        //$fileurl = '/tmp/';//临时存储目录定义 zaki 注释
        //zaki 2015-2-3 start
        $fileurl = UPLOADS.$this->wx_system_user_id."/".$type."/";
        //检查是否存在目录 不存在则创建目录
        $re = Lib_base::createDir($fileurl);
        if(empty($re)){
            return json_encode(array('errcode' => '00010', 'errmsg' => '创建目录失败'));
        }
        //zaki 2015-2-3 end
        //格式 @todo  // image/jpeg  audio/mp3|audio/amr video/mpeg4 image/jpeg
        //文件大小 字节（Byte）
        /*
            图片（image）: 128K，支持JPG格式
            语音（voice）：256K，播放长度不超过60s，支持AMR\MP3格式
            视频（video）：1MB，支持MP4格式
            缩略图（thumb）：64KB，支持JPG格式
        */
        $size_arr = array(
                'image' => 128*1024,//128KB
                'voice' => 256*1024,//256KB
                'video' => 1024*1024,//1MB
                'thumb' => 64*1024//64KB
            );
        $type_arr = array( 
                'image' => array(
                    'jpeg' => 'image/jpeg',
                    'pjeg' => 'image/pjpeg'
                ),
                'voice' => array(
                    'amr' => 'application/octet-stream',// audio/amr
                    'mp3' => 'audio/mp3',
                    'xmp3' => 'audio/mpeg',
                ),
                //'video' => 'video/mpeg4',
                'thumb' => array(
                    'jpeg' => 'image/jpeg',
                    'pjeg' => 'image/pjpeg'
                )
            );
        //文件缺失
        if (!is_uploaded_file($myfile)) {
            return json_encode(array('errcode' => '00001', 'errmsg' => '没有要上传的文件！'));
        }
        //多媒体文件类型限制
        $imageinfo = @getimagesize($upfile['tmp_name']);
        $filetype = ($type == "image")?$imageinfo['mime']:$upfile["type"];
        $is_allow_type = isset($type_arr[$type])?in_array($filetype,$type_arr[$type]):false;
        //error_log(print_r(array($type_arr, $upfile, $is_allow_type, $filetype), true), 3, '/tmp/pinqu_upload_error.log');
        if (!isset($size_arr[$type]) || !$is_allow_type) {
            return json_encode(array('errcode' => '00002', 'errmsg' => '不允许的文件类型！'));
        }
        //文件大小限制
        if ($size > $size_arr[$type]) {
            return json_encode(array('errcode' => '00003', 'errmsg' => '文件大小超过限制！'));
        }
        $newname = md5(time().mt_rand(0,time()));
        @move_uploaded_file($myfile,$fileurl.$newname.'.'.$suffix);
        //移动失败
        if (!file_exists($fileurl.$newname.'.'.$suffix)) {
            return json_encode(array('errcode' => '00004', 'errmsg' => '文件上传失败！'));
        }

        $wx_result = $this->uploadMediaToWx($fileurl.$newname.'.'.$suffix,$type);
         //file_put_contents('/home/sqzhangyujie/test/log.txt',print_r($wx_result,true)."\r\n",FILE_APPEND);
        
        //token无效或者超时
        if (isset($wx_result['errcode']) && in_array($wx_result['errcode'] ,array('40001','42001'))) {
            //获取授权url
            $url = $this->getAccessTokenUrl();
            //重新发送请求获取token
            $token_result = $this->sendRequest($url, 'GET');
            if (isset($token_result['access_token']) && isset($token_result['expires_in'])) {
                //1.获取成功，重新set token
                $this->setAccessToken($token_result['access_token']);
                $this->setExpiresTime($token_result['expires_in']);
                //2.更新数据库
                $expires_time = $token_result['expires_in'] + time();
                $wxinfo = array(
                    'wx_access_token'=> $token_result['access_token'],
                    'expires_time'   => $expires_time
                );
                $rt = wx_system_user::updateinfo($wxinfo,$this->uid);

            } else {//重新获取token失败
                unlink($fileurl.$newname.'.'.$suffix);
                return json_encode(array('errcode' => $wx_result['errcode'], 'errmsg' => Module_Wechat::getErrorMessage($wx_result['errcode'])));
            }
            //重新上传
            $wx_result = $this->uploadMediaToWx($fileurl.$newname.'.'.$suffix,$type);
            if(isset($wx_result['errcode'])){//上传返回错误
                unlink($fileurl.$newname.'.'.$suffix);
                return json_encode(array('errcode' => $wx_result['errcode'], 'errmsg' => Module_Wechat::getErrorMessage($wx_result['errcode'])));
            }
        }

        //微信资源上传成功，提交7牛;
        if (!isset($wx_result['errcode'])) {
            //文件存在
            if(file_exists($fileurl.$newname.'.'.$suffix)) { 
                $qbox = new Lib_Qbox_Qbox();//实例化图片服务器
                $imgid = $qbox ->QboxUp($fileurl.$newname.'.'.$suffix, $suffix);
                if(!$imgid) {
                    //zaki 调整一下删除位置
                    unlink($fileurl.$newname.'.'.$suffix);
                    return json_encode(array('errcode' => '00006', 'errmsg' => '7牛上传失败！'));
                }
                //zaki 2014-02-04 文件重命名 兼容七牛名字
                rename($fileurl.$newname.'.'.$suffix,$fileurl.$imgid.'.'.$suffix);
                //成功返回结果
                //$w_image_ori = Lib_base::image_url($imgid);
                return json_encode(array('wx_result' => $wx_result,'qn_imgid' => $imgid));//,'qn_url' => $w_image_ori));
            } else {
                return json_encode(array('errcode' => '00005', 'errmsg' => '找不到'. $fileurl.$newname.'.'.$suffix .'文件！'));
            }
        } else {//微信上传失败
            unlink($fileurl.$newname.'.'.$suffix);
            return json_encode(array('errcode' => $wx_result['errcode'], 'errmsg' => Module_Wechat::getErrorMessage($wx_result['errcode'])));
        }
    }

    /**
     * 创建分组
     * @param  [type] $name 分组名称
     * @return [type]       [description]
     */
    public function createGroup($name) {
        $url = sprintf($this->user_group_create_url, $this->access_token);
        $array = array(
            "group" => array('name' => $name)
            );
        $temp = $this->json_encode($array);
        return $this->sendRequest($url, 'POST',$temp);
    }

    /**
     * 编辑分组名称
     * @param  [type] $group_id 分组ID
     * @param  [type] $name 分组名称
     * @return [type]           [description]
     */
    public function updateGroup($group_id,$name){
        $url = sprintf($this->user_group_edit_url, $this->access_token);
        $array = array(
            "group" => array(
                "id" => $group_id,
                'name' => $name
                )
            );
        $temp = $this->json_encode($array);
        return $this->sendRequest($url, 'POST',$temp);

}

    /**
     * 取得用户分组
     * @param  [type] $openid 用户openid
     * @return [type]         [description]
     */
    public function getUserGroup($openid){
        $url = sprintf($this->user_group_get_url, $this->access_token);
        $array = array("openid"=>$openid);
        $temp = $this->json_encode($array);
        return $this->sendRequest($url, 'POST',$temp);
    }

    /**
     * 用户移动分组
     * @param  [type] $opendid    [description]
     * @param  [type] $to_groupid [description]
     * @return [type]             [description]
     */
    public function userMoveGroup($openid,$to_groupid){
        $url = sprintf($this->user_group_move_url, $this->access_token);
        $array = array("openid"=>$openid,"to_groupid"=>$to_groupid);
        $temp = $this->json_encode($array);
        return $this->sendRequest($url, 'POST',$temp);
    }

    /**
     * 图文数据统计（时间跨度暂时1天）（获取图文群发总数据）
     * @param  [type]     $start_time  开始时间   例如 2015-03-20
     * @param  [type]     $end_time    结束时间   例如 2015-03-20
     * @return [type]                 [description]
     *
     * @author  zaki
     * @date   2015-03-20
     */
    public function get_news_total_stat($start_time,$end_time){

        $url = sprintf($this->news_total_stat_url, $this->access_token);
        $array = array("begin_date"=>$start_time,"end_date"=>$end_time);
        $temp = $this->json_encode($array);
        return $this->sendRequest($url, 'POST',$temp);

    }

    /**
     * 图文数据统计（时间跨度暂时7天）（获取用户增减数据）
     * @param  [type]     $start_time  开始时间   例如 2015-03-20
     * @param  [type]     $end_time    结束时间   例如 2015-03-20
     * @return [type]                 [description]
     *
     * @author  zaki
     * @date   2015-03-20
     */
    public function get_user_summary_stat($start_time,$end_time){
        
        $url = sprintf($this->user_summary_url, $this->access_token);
        $array = array("begin_date"=>$start_time,"end_date"=>$end_time);
        $temp = $this->json_encode($array);
        return $this->sendRequest($url, 'POST',$temp);
    }
    
    
    
    /**
     * 微信多媒体资源和七牛综合文件上传。
     *
     * @access public
     * @param  $upfile  array 要上传的文件 $_FILES 接收的值
     * @param  $type    string 资源文件类型（缺省：image）
     * @param  $desc    array 上传视频时需要的描述
     * @param $fileName  微信显示的名字
     * @return json  返回结果
     */
    public function uploadMediaToWxAndQnNew($upfile, $type = 'image',$desc = null,$fileName=null){
        if(empty($this->wx_system_user_id)){
            return json_encode(array('errcode' => '00009', 'errmsg' => '缺少公众号唯一ID'));
        }
        $name=$upfile["name"];
        //$filetype=$upfile["type"];
        $suffix = end(explode('.', $name));
        $size=$upfile["size"];
        $myfile=$upfile["tmp_name"];
        //$fileurl = '/tmp/';//临时存储目录定义 zaki 注释
        //zaki 2015-2-3 start
        $fileurl = UPLOADS.$this->wx_system_user_id."/".$type."/";
        //检查是否存在目录 不存在则创建目录
        $re = Lib_base::createDir($fileurl);
        if(empty($re)){
            return json_encode(array('errcode' => '00010', 'errmsg' => '创建目录失败'));
        }
        //zaki 2015-2-3 end
        //格式 @todo  // image/jpeg  audio/mp3|audio/amr video/mpeg4 image/jpeg
        //文件大小 字节（Byte）
        /*
                         图片（image）: 2MB，支持JPG格式
                         语音（voice）：5MB，播放长度不超过60s，支持AMR\MP3格式
                         视频（video）：10MB，支持MP4格式
                         缩略图（thumb）：64KB，支持JPG格式
         */
        $size_arr = array(
        'image' => 2*1024*1024,//2MB
        'voice' => 5*1024*1024,//5MB
        'video' => 10*1024*1024,//10MB
        'thumb' => 64*1024//64KB
        );
        $type_arr = array(
            'image' => array(
                'jpeg' => 'image/jpeg',
                'pjeg' => 'image/pjpeg',
                'png' =>'image/png',
                'bmp'  =>'image/bmp',
                'gif'  =>'image/gif'
            ),
            'voice' => array(
                'amr'  => 'application/octet-stream',// audio/amr
                'mp3'  => 'audio/mp3',
                'xmp3' => 'audio/mpeg',
                'wav'  =>'audio/wav',
                'wma'  =>'audio/x-ms-wma'
            ),
            'video' => array('video/mpeg4','video/mp4'),
            'thumb' => array(
                'jpeg' => 'image/jpeg',
                'pjeg' => 'image/pjpeg'
            )
        ); 
        //文件缺失
        if (!is_uploaded_file($myfile)) {
            return json_encode(array('errcode' => '00001', 'errmsg' => '没有要上传的文件！'));
        }
        //多媒体文件类型限制
        $imageinfo = @getimagesize($upfile['tmp_name']);
        $filetype = ($type == "image")?$imageinfo['mime']:$upfile["type"];
        $is_allow_type = isset($type_arr[$type])?in_array($filetype,$type_arr[$type]):false;
        if (!isset($size_arr[$type]) || !$is_allow_type) {
            return json_encode(array('errcode' => '00002', 'errmsg' => '不允许的文件类型！'));
        }
        //文件大小限制
        if ($size > $size_arr[$type]) {
            return json_encode(array('errcode' => '00003', 'errmsg' => '文件大小超过限制！'));
        }
        $newname = md5(time().mt_rand(0,time()));
        @move_uploaded_file($myfile,$fileurl.$newname.'.'.$suffix);
        //移动失败
        if (!file_exists($fileurl.$newname.'.'.$suffix)) {
            return json_encode(array('errcode' => '00004', 'errmsg' => '文件上传失败！'));
        }
        $fVoiceTimeLong = 0;
        //验证语音时长
        if ($type == 'voice'){
            //获取语音时长
            $dirFile = ROOT."tools/duration.php";
            $command = SCRIPT_PHP55." ".$dirFile."  -w ".$this->wx_system_user_id." -f ".$newname.'.'.$suffix;
            $fVoiceTimeLong = exec($command);
        
            if(!empty($fVoiceTimeLong) && ($fVoiceTimeLong >=60)){
                //return json_encode(array('errcode' => '00007', 'errmsg' => '语音时长不能超过60秒'));
				return json_encode(array('errcode' => '00007', 'errmsg' => '语音大小不超过5M，长度不超过60s，支持mp3/wma/wav/amr模式'));
            }
        }
       // $wx_result = $this->uploadMediaToWx($fileurl.$newname.'.'.$suffix,$type);
        $wx_result = $this->uploadPermanentMediaToWx($fileurl.$newname.'.'.$suffix,$type,$desc,$fileName);
        /*//素材增加图片接口 先注释
        $tmp_img_url = $this->uploadimg_url_news($fileurl.$newname.'.'.$suffix,$fileName);
		$tmp_img_url  = json_decode($tmp_img_url,true);
        if(isset($tmp_img_url["url"])){
            $pic_img_url = $tmp_img_url["url"];
        }else{
            $pic_img_url = '';
        }*/
       //  file_put_contents('/home/sqzhangyujie/test/log.txt',print_r($wx_result,true)."wx\r\n",FILE_APPEND);
        //token无效或者超时
        if (isset($wx_result['errcode']) && in_array($wx_result['errcode'] ,array('40001','42001'))) {
            //获取授权url
            $url = $this->getAccessTokenUrl();
            //重新发送请求获取token
            $token_result = $this->sendRequest($url, 'GET');
            if (isset($token_result['access_token']) && isset($token_result['expires_in'])) {
                //1.获取成功，重新set token
                $this->setAccessToken($token_result['access_token']);
                $this->setExpiresTime($token_result['expires_in']);
                //2.更新数据库
                $expires_time = $token_result['expires_in'] + time();
                $wxinfo = array(
                    'wx_access_token'=> $token_result['access_token'],
                    'expires_time'   => $expires_time
                );
                $rt = wx_system_user::updateinfo($wxinfo,$this->uid);
    
            } else {//重新获取token失败
                unlink($fileurl.$newname.'.'.$suffix);
                return json_encode(array('errcode' => $wx_result['errcode'], 'errmsg' => Module_Wechat::getErrorMessage($wx_result['errcode'])));
            }
            //重新上传
           // $wx_result = $this->uploadMediaToWx($fileurl.$newname.'.'.$suffix,$type);
            $wx_result = $this->uploadPermanentMediaToWx($fileurl.$newname.'.'.$suffix,$type,$desc);
            /*//素材增加图片接口 先注释
            $tmp_img_url = $this->uploadimg_url_news($fileurl.$newname.'.'.$suffix,$fileName);
			$tmp_img_url  = json_decode($tmp_img_url,true);
            if(isset($tmp_img_url["url"])){
                $pic_img_url = $tmp_img_url["url"];
			}else{
                $pic_img_url = '';
            }*/
            //file_put_contents('/home/sqzhangyujie/test/log.txt', $fileurl.$newname.'.'.$suffix."\r\n",FILE_APPEND);
            if(isset($wx_result['errcode'])){//上传返回错误
                unlink($fileurl.$newname.'.'.$suffix);
                return json_encode(array('errcode' => $wx_result['errcode'], 'errmsg' => Module_Wechat::getErrorMessage($wx_result['errcode'])));
            }
        }
        //微信资源上传成功，提交7牛;
        if (!isset($wx_result['errcode'])) {
            //文件存在
            if(file_exists($fileurl.$newname.'.'.$suffix)) {
                
                $qbox = new Lib_Qbox_Qbox();//实例化图片服务器
                $imgid = $qbox ->QboxUp($fileurl.$newname.'.'.$suffix, $suffix);
                if(!$imgid) {
                    //zaki 调整一下删除位置
                    unlink($fileurl.$newname.'.'.$suffix);
                    //删除微信 add zhangyujie
                   $delJson = $this->delPermanentMediaOnWx($wx_result['media_id']);
                   if(!empty($delJson['errcode'])){
                       //记录日志
                       //file_put_contents('/home/sqzhangyujie/test/log.txt', date("Y-m-d h:i:s",time()).'  '.$wx_result['media_id']."\r\n",FILE_APPEND);
                        
                   }
                    return json_encode(array('errcode' => '00006', 'errmsg' => '7牛上传失败！'));
                }
                //zaki 2014-02-04 文件重命名 兼容七牛名字
               //截取缩略图
                $thumbId = ''; //给张默认的缩略图
                if($type=='video'){
                   $thumb_Id =  $this->InterceptThumb($fileurl.$newname.'.'.$suffix);
                   if(!empty($thumb_Id)){
                       $thumbId = $thumb_Id;
                       unlink($fileurl.$newname.'.'.$suffix); // add zhangyujie
                   }
                }else{
                    unlink($fileurl.$newname.'.'.$suffix); // add zhangyujie
                }
                //成功返回结果
                //$w_image_ori = Lib_base::image_url($imgid);
                return json_encode(array('wx_result' => $wx_result,'qn_imgid' => $imgid,'thumbId'=>$thumbId,'fVoiceTimeLong'=>$fVoiceTimeLong));//,'qn_url' => $w_image_ori));
				/*zhouzhou  2015年8月3日16:56:17  ,"pic_img_url"=>$pic_img_url*/
				
            } else {
                return json_encode(array('errcode' => '00005', 'errmsg' => '找不到'. $fileurl.$newname.'.'.$suffix .'文件！'));
            }
        } else {//微信上传失败
            unlink($fileurl.$newname.'.'.$suffix);
            return json_encode(array('errcode' => $wx_result['errcode'], 'errmsg' => Module_Wechat::getErrorMessage($wx_result['errcode'])));
        }
    }
    /**
     * 上传永久素材
     * @author zhangyujie
     * @param string $media
     * @param string $type  媒体文件mime类型 Format    image|voice|video|thumb
     * @param $description   上传视频的时候需要  array('title'=>'','introduction'=>'')
     * @param $fileName   文件名字
     * @return string json
     */
    
    public function uploadPermanentMediaToWx($media, $type = 'image',$description=array(),$fileName=null){
        if (empty($this->access_token) || empty($media)) {
            return false;
        }
        $param['media'] = '@'.$media.';filename='.$fileName;
        $param['type']  = $type;
    
        if(!empty($description) && $type=='video'){
            $param['description'] = $this->json_encode($description);
        }
        $media_post_url = sprintf($this->media_permanent_upload_url, $this->access_token);
        //file_put_contents('/home/sqzhangyujie/test/log.txt', $param['description']."\r\n",FILE_APPEND);
        return $this->sendRequest($media_post_url, 'POST', $param);
    }
    
    /**
     * 删除微信永久素材
     * @param string  $mediaId
     * @author zhangyujie
     */
    public function delPermanentMediaOnWx($mediaId){
        if (empty($this->access_token) || empty($mediaId)) {
            return false;
        }
        $param['media_id']      = $mediaId;
        $media_post_url         = sprintf($this->del_media_permanent_url, $this->access_token);
        $body = json_encode($param);
        return $this->sendRequest($media_post_url, 'POST', $body);
    }
    
    /**
     * 截取视频的缩略图
     * $file string 视频的路径
     */
    public function InterceptThumb($file){
          $thumbId = '';
        return '6dbf9b7a39feac66ff448d81cee8fab8';
        if(extension_loaded('ffmpeg')){//判断ffmpeg是否载入
            //$mov = new ffmpeg_movie('/home/sqzhangyujie/a.mp4');//视频的路径
            $mov = new ffmpeg_movie($file);//视频的路径
            //dump($mov);
            $ff_frame = $mov->getFrame(1);
            $gd_image = $ff_frame->toGDImage();
            //$img=$_SERVER['DOCUMENT_ROOT']."/b2.jpg";//要生成图片的绝对路径
            $name = time();
           // $img="/home/sqzhangyujie/test/".$name.".jpg";//要生成图片的绝对路径
            $fileurl = UPLOADS.$this->wx_system_user_id."/image/";
            $img = $fileurl.$name.".jpg";
            imagejpeg($gd_image, $img);//创建jpg图像
            imagedestroy($gd_image);//销毁一图像
           
            if(file_exists($img)){
                //成功 //上传到七牛
                $qbox = new Lib_Qbox_Qbox();//实例化图片服务器
                $thumbId = $qbox ->QboxUp($img, 'jpg');
                if(!empty($thumbId)){
                    unlink($img);
                }
            }else{
                //失败//记录日志
                $logFile = ROOT.'logs/thumb.txt';
                $str     = date("Y-m-d h:i:s",time()).' '.$file."\r\n";
                $handle  = fopen($logFile, "a+");
                if($handle!==false){
                    $num = fwrite($handle, $str);
                    if($num === false){
                        //echo "文件写入失败";
                        //file_put_contents(ROOT.'logs/thumb.txt', date("Y-m-d h:i:s",time()).'  '.$file."\r\n",FILE_APPEND);
                    }
                    fclose($handle);
                }else{
                   // echo "不能打开文件";
                   //file_put_contents(ROOT.'logs/thumb.txt', date("Y-m-d h:i:s",time()).'  '.$file."\r\n",FILE_APPEND);
                }
            }
        }else{
           // echo "ffmpeg没有载入";
            
        }
        return $thumbId;
        
    }
	public function materialNum(){
        if (empty($this->access_token)) {
            return false;
        }
        $material_num_url = sprintf($this->material_num_url, $this->access_token);
        return $this->sendRequest($material_num_url, 'POST');
    }
	
	public function batchget_material($type,$offset,$count){
        if (empty($this->access_token)) {
            return false;
        }
        $batchget_material_url = sprintf($this->batchget_material_url, $this->access_token);
		$array = array("type"=>$type,"offset"=>$offset,"count"=>$count);
        $temp = $this->json_encode($array);
        return $this->sendRequest($batchget_material_url, 'POST',$temp);
    }
	public function get_material($media_id){
        if (empty($this->access_token)) {
            return false;
        }
        $get_material_url = sprintf($this->get_material_url, $this->access_token);
		$array = array("media_id"=>$media_id);
        $temp = $this->json_encode($array);
        return $this->sendRequest($get_material_url, 'POST',$temp,'file');
    }
	/**
     * 上传图文永久素材
     * @access public
     * @param  $articles_json  json格式图文消息素材
     * @return string  返回结果
     */
    public function add_news($articles_json){
        if (empty($this->access_token) || empty($articles_json)) {
            return false;
        }
        $add_news_url = sprintf($this->add_news_url, $this->access_token);
        return $this->sendRequest($add_news_url, 'POST', $articles_json);
    }
	/**
     * 修改图文永久素材
     * @access public
     * @param  $articles_json  json格式图文消息素材
     * @return string  返回结果
     */
    public function update_news($articles_json){
        if (empty($this->access_token) || empty($articles_json)) {
            return false;
        }
        $update_news_url = sprintf($this->update_news_url, $this->access_token);
        return $this->sendRequest($update_news_url, 'POST', $articles_json);
    }
	/**
     * 删除永久素材
     * @access public
     * @param  $articles_json  json格式图文消息素材
     * @return string  返回结果
     */
    public function del_material($media_id){
        if (empty($this->access_token) || empty($media_id)) {
            return false;
        }
        $del_material_url = sprintf($this->del_material_url, $this->access_token);
		$array = array("media_id"=>$media_id);
        $temp = $this->json_encode($array);
        return $this->sendRequest($del_material_url, 'POST', $temp);
    }
    	
	/**
     * oauth 获取页面授权跳转地址
	 * @author chenhao
     * @param string $callback 回调URI
     * @param string $state 自定义回传参数 a-zA-Z0-9 128字节 
     * @param string $scope snsapi_base（不弹出授权页面，直接跳转，只能获取用户openid） snsapi_userinfo （弹出授权页面，可获得所有信息）默认snsapi_userinfo 
     * @return string
     */
    public function get_oauth_redirect($callback,$state='',$scope='snsapi_userinfo'){
        return $url = sprintf($this->oauth_redirect_url, $this->appid,urlencode($callback),$scope,$state);
    }
   
    /**
     * 通过code获取Access Token
	 * @author chenhao
     * @return array {access_token,expires_in,refresh_token,openid,scope}
     */
    public function get_oauth_access_token($code){
        $url = sprintf($this->oauth_token_url, $this->appid, $this->secret, $code);
        return $this->sendRequest($url, 'POST');
    }
	
	/**
	 * 获取授权后的用户资料
	 * @author chenhao
	 * @param string $access_token
	 * @param string $openid
	 * @return array {openid,nickname,sex,province,city,country,headimgurl,privilege,[unionid]}
	 * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
	 */
    public function get_oauth_userinfo($access_token,$openid){
        $url = sprintf($this->oauth_userinfo_url, $access_token, $openid);
		return $this->sendRequest($url, 'POST');
    }
	/**
     * 获取jssdk
     * @access public
     * @return string  返回结果
     */
    public function getticket_url(){
         if (empty($this->access_token)) {
            return false;
        }
        $getticket_url = sprintf($this->getticket_url, $this->access_token);
        return $this->sendRequest($getticket_url, 'GET');
    }
	/**
     * 上传图片接口
     * @author zhouxiangyu
     * @param string $media
     * @param $description   上传视频的时候需要  array('title'=>'','introduction'=>'')
     * @param $fileName   文件名字
     * @return string json
     */
    public function uploadimg_url($upfile, $type = 'image',$fileName=null){
        if(empty($this->wx_system_user_id)){
            return json_encode(array('errcode' => '00009', 'errmsg' => '缺少公众号唯一ID'));
        }
        $name=$upfile["name"];
        $suffix = end(explode('.', $name));
        $size=$upfile["size"];
        $myfile=$upfile["tmp_name"];
        $fileurl = UPLOADS.$this->wx_system_user_id."/".$type."/";
        //检查是否存在目录 不存在则创建目录
        $re = Lib_base::createDir($fileurl);
        if(empty($re)){
            return json_encode(array('errcode' => '00010', 'errmsg' => '创建目录失败'));
        }
        $size_arr = array(
        'image' => 1*1024*1024,//2MB
        );
        $type_arr = array(
            'image' => array(
                'jpeg' => 'image/jpeg',
                'pjeg' => 'image/pjpeg',
                'png' =>'image/png'
            )
        ); 
        //文件缺失
        if (!is_uploaded_file($myfile)) {
            return json_encode(array('errcode' => '00001', 'errmsg' => '没有要上传的文件！'));
        }
        //多媒体文件类型限制
        $imageinfo = @getimagesize($upfile['tmp_name']);
        $filetype = ($type == "image")?$imageinfo['mime']:$upfile["type"];
        $is_allow_type = isset($type_arr[$type])?in_array($filetype,$type_arr[$type]):false;
        if (!isset($size_arr[$type]) || !$is_allow_type) {
            return json_encode(array('errcode' => '00002', 'errmsg' => '不允许的文件类型！'));
        }
        //文件大小限制
        if ($size > $size_arr[$type]) {
            return json_encode(array('errcode' => '00003', 'errmsg' => '文件大小超过限制！'));
        }
        $newname = md5(time().mt_rand(0,time()));
        @move_uploaded_file($myfile,$fileurl.$newname.'.'.$suffix);
        //移动失败
        if (!file_exists($fileurl.$newname.'.'.$suffix)) {
            return json_encode(array('errcode' => '00004', 'errmsg' => '文件上传失败！'));
        }
		$wx_result = $this->uploadimg_url_news($fileurl.$newname.'.'.$suffix,$fileName);
		
		/*$media = $fileurl.$newname.'.'.$suffix;
        $param['media'] = '@'.$media.';filename='.$fileName;
        $media_post_url = sprintf($this->uploadimg_url, $this->access_token);
        $re = $this->sendRequest($media_post_url, 'POST', $param);*/
		$wx_result = json_decode($wx_result,true);
		if (isset($wx_result['errcode']) && in_array($wx_result['errcode'] ,array('40001','42001'))) {
            //获取授权url
            $url = $this->getAccessTokenUrl();
            //重新发送请求获取token
            $token_result = $this->sendRequest($url, 'GET');
            if (isset($token_result['access_token']) && isset($token_result['expires_in'])) {
                //1.获取成功，重新set token
                $this->setAccessToken($token_result['access_token']);
                $this->setExpiresTime($token_result['expires_in']);
                //2.更新数据库
                $expires_time = $token_result['expires_in'] + time();
                $wxinfo = array(
                    'wx_access_token'=> $token_result['access_token'],
                    'expires_time'   => $expires_time
                );
                $rt = wx_system_user::updateinfo($wxinfo,$this->uid);
				$wx_result = $this->uploadimg_url_news($fileurl.$newname.'.'.$suffix,$fileName);
				return $wx_result;
            }
        }
		$wx_result = json_encode($wx_result);
		return $wx_result;
    }
	public function uploadimg_url_news($img_info,$fileName=null){
		//$img_info = $fileurl.$newname.'.'.$suffix;
		$post_img['media'] = '@'.$img_info.';filename='.$fileName;
		$media_post_url = sprintf($this->uploadimg_url, $this->access_token);
		$re = $this->sendRequest($media_post_url, 'POST', $post_img);
		return json_encode($re);
	}
	/**
	 *@note 查询群发消息发送状态
	 *@date 2015/7/28
	 */
	public function searchMassStatus($msg_id){
		if (empty($this->access_token) || empty($msg_id)) {
            return false;
        }
        $search_mass_status_url = sprintf($this->search_mass_status_url, $this->access_token);
		$array = array("msg_id"=>$msg_id);
        $temp = $this->json_encode($array);
        return $this->sendRequest($search_mass_status_url, 'POST',$temp);
	}
    
}
?>
