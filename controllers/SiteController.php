<?php

namespace app\controllers;
use app\components\Codes;
use app\components\BlacklistRule;

use app\models\AdminUser;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex() {
        echo 123;exit;
        $user = AdminUser::findIdentity(1);
        Yii::$app->user->login($user, 111);

    }

    public function actionCodes() {
        echo "
<p>生成兑奖码:</p>
<form action='/index.php?r=site/encode' method='post' target='_blank'>
    <input name='_csrf' type='hidden' id='_csrf' value='". (Yii::$app->request->csrfToken)."'/>
    sku:<input name='sku' type='text' value=''/>
    数量:<input name='count' type='number' max='50000' value=''/>
    <!--
    开始时间:<input name='start_time' type='date' value=''/>
    结束时间:<input name='end_time' type='date' value=''/>
    -->
    <input type='submit' value='生成'/ >
</form><br/>

<p>验证兑奖码:</p>
<form action='/index.php?r=site/verify' method='post' target='_blank'>
    <input name='_csrf' type='hidden' id='_csrf' value='". (Yii::$app->request->csrfToken)."'/>
    动作:<select name='action' >
    <option value='' selected>验证</used>
    <option value='used'>使用</used>
    <option value='recovered'>回收</used>
    </select>
    兑奖码:<input name='code' type='text' value=''/>
    <input type='submit' value='验证'/ >
</form><br/>
            ";
    }

    public function actionEncode() {
        $sku = $_POST['sku'];
        $count = $_POST['count'];
        //$start_time = $_POST['start_time'];
        //$end_time = $_POST['end_time'];
        $rsEncode = (Codes::generate($sku, $count, 12, time(), time()+3600*24*30));
        echo "<pre>";
        print_r($rsEncode);
        if(empty($rsEncode['errcode'])) {
            echo "\n生成码列表：\n";
            echo file_get_contents($rsEncode['data']['out_file']);
        }
        // var_dump($rsEncode);
    }

    public function actionVerify() {
        $action = $_POST['action'];
        $code = $_POST['code'];
        echo "<pre>";
        print_r(Codes::verify($code, $action));
        
    }
}
