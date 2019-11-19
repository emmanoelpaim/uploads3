<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use app\models\ContactForms;
use app\models\Api;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $model = new LoginForm();
        $api = new Api();
        $success = "";

        if (Yii::$app->request->isPost) {

            $model->file = UploadedFile::getInstance($model, 'file');
            $model->name = Yii::$app->request->post()["LoginForm"]["name"];

            if ($model->file) {
                $api->uploadFile();
                $success = "Criado com sucesso";
            }
            if($model->name){
                $api->deleteFile($model->name);
                $success = "Deletado com sucesso";
            }

            return $this->render('index',['model'=>$model,'success'=>$success]);
        }

        return $this->render('index',['model'=>$model,'success'=>false]);
    }

}
