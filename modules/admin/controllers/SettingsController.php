<?php

namespace app\modules\admin\controllers;

use app\components\Settings;
use Yii;
use app\models\Server;
use app\modules\admin\models\ServerSearch;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ServerController implements the CRUD actions for Server model.
 */
class SettingsController extends Controller
{
    //模板路径
    public $layout = '@app/modules/admin/views/layouts/admin';
    public $assetsPath = '';

    /**
     * Lists all Server models.
     * @return mixed
     */
    public function actionIndex()
    {
        $setting = new Settings;

        if ( isset($_POST['Settings']) ) {
            $data   = $_POST['Settings'];
            foreach($data as &$v) {
                $v = Html::encode($v);
            }
            $setting->set('site', $data);
        }
        $settings   = $setting->get('site');
        return $this->render('index', ['settings' => $settings]);
    }
}
