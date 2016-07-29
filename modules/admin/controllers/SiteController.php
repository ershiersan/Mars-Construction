<?php

namespace app\modules\admin\controllers;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public $enableCsrfValidation = false;
    public $layout = 'admin';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','logout', 'fileupload'],
                        'allow' => true,
                        'roles' => ['@'],

                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {

//        $x = Yii::$app->request->getBaseUrl();
//        $x=  Yii::$app->urlManager->createUrl('index');
        // var_dump($x);exit;
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = false;
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\modules\admin\models\LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/admin']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['/admin/site/login']);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionFileupload()
    {
        $msg = 'success';
        $code = 1;
        try {
            $fileId = Yii::$app->get('fileManager')->putFile($_FILES['file']['tmp_name'], $_FILES['file']['name']);
            $url = Yii::$app->get('fileManager')->getFileUrl($fileId);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $code = 1001;
        }

        $data = [
            'code' => $code,
            'msg' => $msg,
            'data' => [
                'fileId' => $fileId,
                'url' => $url,
            ]
        ];

        echo json_encode($data);
        Yii::$app->end();
    }
}
