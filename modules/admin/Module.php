<?php

namespace app\modules\admin;
use Yii;
use yii\helpers\Url;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultRoute = "site";
    public $layout = 'admin';
    public function init()
    {
        parent::init();
        // custom initialization code goes here
        \Yii::$app->setComponents([
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'app\models\AppRoleUser',
                'identityCookie' => ['name' => '_admin'],
                'idParam' => '__adminh5',
                'enableSession' => true,
                'enableAutoLogin' => true,
                'loginUrl' => ['admin/site/login']
            ],

        ]);
        Yii::configure($this,[
            'modules' => [
                'mdm' => [
                    'class' => 'mdm\admin\Module',
                    'layout' => 'left-menu',
                    'menus' => [
                        'assignment' => [
                            'label' => '用户列表' // change label
                        ],
                        'rule' => null, // disable menu route
                        'admin' => [
                            'label' => '回到管理页', // change label
                            'url' => ['/admin/site']
                        ],
                    ],
                    'controllerMap' => [
                        'assignment' => [
                            'class' => 'mdm\admin\controllers\AssignmentController',
                            'usernameField' => 'email',
                            'idField' => 'userid', // id field of model User
                        ]
                    ],
                ],
            ],
            'as access' => [
                'class' => 'mdm\admin\components\AccessControl',
                'allowActions' => [
                    'site/*',
                ]
            ],
        ]);
    }

    public function beforeAction($action) {
        if(!parent::beforeAction($action)) {
            return false;
        }

        /* if(Yii::$app->user->isGuest && Yii::$app->controller->getRoute() != 'admin/site/login') {
            Yii::$app->user->setReturnUrl(Url::to('/'.[Yii::$app->controller->getRoute()], 'http'));
            Yii::$app->end(0, Yii::$app->getResponse()->redirect(Url::to(['/admin/site/login'], 'http')));
        } */
        return true;
    }

}
