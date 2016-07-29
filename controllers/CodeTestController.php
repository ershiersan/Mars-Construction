<?php

namespace app\controllers;
use Yii;
use yii\web\Controller;

class CodeTestController extends Controller
{
    public function actionIndex($code) {
        echo "
<script type='text/javascript'>            
var code = '{$code}';
var url= location.href;
document.write('CODE：'+code+\"<br/>LENGTH：\"+(code.length)+'<br/><br/>URL：'+url+\"<br/>LENGTH：\"+(url.length)+'<br/><br/>注：该域名长度与c2.topchef.net.cn长度相同');
</script>
";
    }
}
