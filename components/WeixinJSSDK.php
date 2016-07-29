<?php
namespace app\components;

use Yii;

class WeixinJSSDK {
    public $appId = 'wx0010bae6595635fc';
    
    public function shorturl($longUrl) {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=$accessToken";
        $params = [
            'action' => 'long2short',
            'long_url' => $longUrl,
        ];

        return json_decode($this->httpPost($url, json_encode($params)), true);
    }

    //查询设备cid 查询页面
    public function getQueryRelationByCid($cid){
        $cid = 2207958;
        //$type =1;//按照设备id查页面  type=2 按页面查设备id
        $token = $this->getAccessToken();
        //查询设备接口
        $url = 'https://api.weixin.qq.com/shakearound/relation/search?access_token=' . $token;
        $params = [
            'type'  => 1,
            'device_identifier' => [
                'device_id' => $cid,
//                'uuid' => "FDA50693-A4E2-4FB1-AFCF-C6EB07647825",
//                'major' => 10024,
//                'minor' => 9642
            ]
        ];
        return json_encode($this->httpPost($url, json_encode($params)), true);
    }

    //查询设备cid 查询页面
    public function getQueryRelationByPageId($cid){
        $pageId = 2207958;
        //$type =1;//按照设备id查页面  type=2 按页面查设备id
        $token = $this->getAccessToken();
        //查询设备接口
        $url = 'https://api.weixin.qq.com/shakearound/relation/search?access_token=' . $token;
        $params = [
            'type'  => 2,
            'page_id' => $cid, //指定的页面id；当type为2时，此项为必填
            'begin' => 0, //关联关系列表的起始索引值；当type为2时，此项为必填
            'count' => 2 //待查询的关联关系数量，不能超过50个；当type为2时，此项为必填
        ];
        return json_encode($this->httpPost($url, json_encode($params)), true);
    }

    //配置设备与页面的关联
    public function setPageRelation($uuid, $major, $minor,$pageId){
        $token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/shakearound/device/bindpage?access_token=' . $token;
        $params = [
          'device_identifier' => [
              //'device_id' =>$cid,
              'uuid' => $uuid,
              'major' => $major,
              'minor' => $minor
          ],
            'page_ids' => $pageId //固定page_id
        ];

        return json_encode($this->httpPost($url, json_encode($params)), true);
    }

    public function getSignPackage($url = '') {

        $jsapiTicket = $this->getJsApiTicket();
        if(!$url){
            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    public function getCardSignPackage($cardId, $code = '') {
        $timestamp = time();

        $data = array();
        array_push($data, (string)$timestamp);
        array_push($data, (string)$cardId);
        array_push($data, (string)$this->getCardTicket());
        array_push($data, (string)$code);
        sort($data, SORT_STRING);
        $signature = sha1(implode($data));
        $signPackage = array(
            'cardId' => $cardId,
            'cardExt' => json_encode(array('code'=>$code, 'openid'=>'','timestamp'=>$timestamp, 'signature'=>$signature)),
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket() {
        $url = "http://admin.haoli168.cn/interface-digital/getjsapi_ticket.php";
        $res = $this->httpGet($url);
        if($res['result'] == 1) {
            return $res['data']['jsapi_ticket'];
        }
        return false;
    }

    private function getCardTicket() {
        //return 'E0o2-at6NcC2OsJiQTlwlCzB20SV9ijFf32aRIrflMtRWpA-eEXsmvwLZxOiZLo8Cmkml7rcU2rqt6YAdSrF0Q';
        $url = "http://admin.haoli168.cn/interface-digital/getapi_ticket.php";
        $res = $this->httpGet($url);
        if($res['result'] == 1) {
            return $res['data']['api_ticket'];
        }
        return false;
    }

    public function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $url = "http://admin.haoli168.cn/interface-digital/gettoken.php";
        $res = $this->httpGet($url);
        if($res['result'] == 1) {
            return $res['data']['access_token'];
        }
        return false;
    }



    function getShakeInfo($ticket) {
        $url=sprintf('https://api.weixin.qq.com/shakearound/user/getshakeinfo?access_token=%s', $this->getAccessToken());
        $res = $this->httpPost($url, json_encode(array(
            "ticket"=>$ticket,
            "need_poi"=>1
        )), array(), true);
        return $res;
    }

    private function httpGet($url, $hasSign = true) {
        if($hasSign) {
            //签名
            $ts = time();
            $key = md5('ufsyingxiaotong'.$ts);
            $sign = md5($key.$ts);
            $params = ['ts' => $ts, 'key' => $key, 'sign' => $sign];
            $url = $url . "?" . http_build_query($params);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return json_decode($res, true);
    }

    private static function httpPost($url, $params = '') {
//        $params = http_build_query($params, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($params)));
        $res = curl_exec($ch);
        $errno = curl_errno($ch);
        $info  = curl_getinfo($ch);
        $info['errno'] = $errno;
        curl_close($ch);
        Yii::info([$info, $params, $res, 'post_info'], 'zhyf');
        return json_decode($res, true);
    }
}

