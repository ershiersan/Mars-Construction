<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--    //设置静态路径-->
    <?PHP list(,$this->params['baseUrl']) = \Yii::$app->assetManager->publish('@app/static');?>
    <link rel="stylesheet" href="<?php echo $this->params['baseUrl'];?>/wap-1/css/public.css" />
    <link rel="stylesheet" href="<?php echo $this->params['baseUrl'];?>/wap-1/css/css.css">
    <script type="text/javascript">
        var $STCONFIG = {
            VERSION 	: '1.0',
            STATIC_URL  : "<?php echo $this->params['baseUrl'];?>/wap-1/"
        };
        if(/Android (\d+\.\d+)/.test(navigator.userAgent)){
            var version = parseFloat(RegExp.$1);
            if(version > 2.3){
                var phoneScale = parseInt(window.screen.width) / 640;
                document.write('<meta name="viewport" content="width=640, minimum-scale = '+ phoneScale +', maximum-scale = '+ phoneScale +', target-densitydpi=device-dpi">');
            }else{
                document.write('<meta name="viewport" content="width=640, target-densitydpi=device-dpi">');
            }
        }else{
            document.write('<meta name="viewport" content="width=640, user-scalable=no, target-densitydpi=device-dpi">');
        }
        if(navigator.userAgent.indexOf('MicroMessenger') >= 0){
            document.addEventListener('WeixinJSBridgeReady', function() {
                //alert(1)
                //WeixinJSBridge.call('hideToolbar');
                WeixinJSBridge.call('hideToolbar');
                WeixinJSBridge.call('hideOptionMenu');
            });
            document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
                WeixinJSBridge.call('hideOptionMenu');
            });
        }
        else{
            //window.location.href = 'D-02.html';
        }
    </script>
    <title><?= Html::encode($this->title)?></title>
    <!--    //引入微信js-->
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
        /**
         *  微信分享配置
         */
        <?php
            $weixin = new \app\components\WeixinJSSDK();
            $signPackage = $weixin->GetSignPackage();
        ?>
        wx.config({
            debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '<?php echo $signPackage["appId"];?>',
            timestamp: '<?php echo $signPackage["timestamp"];?>',
            nonceStr: '<?php echo $signPackage["nonceStr"];?>',
            signature: '<?php echo $signPackage["signature"];?>',
            jsApiList: ['addCard'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        })
        wx.ready(function() {
            wx.hideOptionMenu();

        })

        wx.error(function(res){

        })

    </script>
    <?php $this->head()?>
    </head>
    <body>
    <?php $this->beginBody()?>
    <?= $content?>
    <?php $this->endBody()?>
    </body>
    <script src="<?php echo $this->params['baseUrl'];?>/wap-1/js/sea.js"></script>
    <script src="<?php echo $this->params['baseUrl'];?>/wap-1/js/run.js"></script>
<div style="display: none">
    <script src="http://s11.cnzz.com/stat.php?id=1256407096&web_id=1256407096" language="JavaScript"></script>
</div>
    </html>
<?php $this->endPage()?>
