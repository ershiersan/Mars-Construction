<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\modules\admin\models\AdminUserSearch;
use yii\helpers\Url;
use yii\filters\AccessControl;

class RbacController extends Controller
{
    //模板路径
    public $layout = '@app/modules/admin/views/layouts/admin';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin'],

                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $assignmentUrl = Url::toRoute(['/mdm/assignment']);
        return $this->render('index',
            ['assignmentUrl' => $assignmentUrl]
        );
    }
}
