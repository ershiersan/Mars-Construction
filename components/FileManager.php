<?php
namespace app\components;

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;
class FileManager {
    // 联合利华使用的七牛配置
    public $perfix = "platform/"; //fixme  qiniu
    public $accessKey = 'JX9SmCzBolU6QoIaJt4vAeL48UWrfjsWen-mNce1';
    public $secretKey = '1MAgxnS1AwRVNmK-Ibij6LOrTPbIF0kaghcYnUD8';
    public $bucketName = 'pinqu';
    public $domain = 'http://pinqu.tp.shiqutech.com';

    /**
     * 上传文件
     */
    public function putFile($filePath, $fileName = '', $key = '', $perfix = '') {
        $upManager = new UploadManager();
        $auth = new Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->bucketName);

        if($key == '') {
            $key = substr(sha1(microtime().rand(1,9)), 0, 10);
        }

        if($perfix == ''){
            $key = $this->perfix . $key;
        } else {
            $key = $perfix . $key;
        }

        if($fileName) {
            $fileSuffix = $this->getFileSuffix($fileName);
        } else {
            $fileSuffix = $this->getFileSuffix($filePath);
        }

        if($fileSuffix) {
            $key = $key . '.' . $fileSuffix;
        }
    
        list($ret, $error) = $upManager->putFile($token, $key, $filePath);
        if($ret && isset($ret['key'])) {
            return $ret['key'];
        }
            return false;
        }

    //七牛云获取文件地址
    function getFileUrl($key, $w=0, $h=0, $type=2){
        if(trim($key) == '') {
          return '';
        }
        $fileUrl = $this->domain . '/' . $key;
        
        //  图片处理
        $fileType = self::getFileSuffix($key);
        if(in_array($fileType, array ('jpg','jpeg','gif','png','bmp')) && ($w || $h)){
            $fileUrl .= '?imageView/'.$type;
            if($w)
                $fileUrl .= '/w/'.$w;
            if($h)
                $fileUrl .= '/h/'.$h;
        }
        return $fileUrl;
    }

    /**
     * 获取文件后缀名
     */
    function getFileSuffix($fileName) {
        $matches = [];
        if(preg_match("/\.\w{1,10}$/", $fileName, $matches)) {
            return strtolower(str_replace('.', '', $matches[0]));
        }
        return '';
    }

  //   static function getFileSuffixList() {
  //   	return array(
  //   			'jpg'=>'01', 'jpeg'=>'02', 'gif'=>'03', 'png'=>'04', 'bmp'=>'05',
  //   			'tiff'=>'06',
  //   			'mp3'=>'11', 'mp4'=>'12', 'wav'=>'13', 'wma'=>'14', 'rmvb'=>'15',
  //   			'mvb'=>'16', 'mkv'=>'17', 'ogg'=>'18',
  //   			'doc'=>'21', 'docx'=>'22', 'xls'=>'23', 'xlsx'=>'24', 'ppt'=>'25',
  //   			'pptx'=>'26', 'mpp'=>'27',
  //   			'rar'=>'31', 'zip'=>'32', 'tar'=>'33', '7z'=>'34',
  //   			'psd'=>'41', 'pdf' => '42',
  //   	);
  //   }
    
  //   static function getFileSuffixIdList() {
  //       return array(
		// 	'01'=>'jpg', '02'=>'jpeg', '03'=>'gif', '04'=>'png', '05'=>'bmp',
		// 	'06'=>'tiff',
		// 	'11'=>'mp3', '12'=>'mp4', '13'=>'wav', '14'=>'wma', '15'=>'rmvb',
		// 	'16'=>'mvb', '17'=>'mkv', '18'=>'ogg',
		// 	'21'=>'doc', '22'=>'docx', '23'=>'xls', '24'=>'xlsx', '25'=>'ppt',
		// 	'26'=>'pptx', '27'=>'mpp',
		// 	'31'=>'rar', '32'=>'zip', '33'=>'tar', '34'=>'7z',
		// 	'41'=>'psd', '42' => 'pdf'
		// );
  //   }

    /**
     * 上传文件到本地
     */
    public function putLocalFile($filePath, $fileName = '', $key = '', $needSuffix='csv') {
        $arrFileName = explode('.', $fileName);
        $fileType = $arrFileName[count($arrFileName) - 1];
        /* if($fileType != 'jpg' && $fileType != 'png' && $fileType != 'gif') {
            throw new Exception("只能上传 jpg png gif 格式文件");
        } */


        if($key == '') {
            $key = substr(sha1(microtime().rand(1,9)), 0, 10);
        }

        if($fileName) {
            $fileSuffix = $this->getFileSuffix($fileName);
        } else {
            $fileSuffix = $this->getFileSuffix($filePath);
}
        if($fileSuffix != $needSuffix)
            return ['error' => '格式错误，请重新上传'];

        $fileSuffix = $fileType;

        if( strtolower($fileSuffix) == 'php') {
            return false;
    }

        if($fileSuffix) {
            $key = $key . '.' . $fileSuffix;
        }


        if (!is_dir(\Yii::getAlias('@webroot/upload/'))) {
            \yii\helpers\FileHelper::createDirectory(\Yii::getAlias('@webroot/upload/'));
            }
        $localPath = \Yii::getAlias('@webroot/upload/' . $key);

        if (copy($filePath, $localPath) === false) {
            return false;
        }
        @chmod($localPath, 0644);

        return [
            'key' => $key,
            'path' => $localPath,
        ];
    }

    // 获取本地文件
    function getLocalFilePath($key){
        return \Yii::getAlias('@webroot/upload/' . $key);
    }
}