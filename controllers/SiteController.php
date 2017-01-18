<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
//use app\models\LoginForm;
//use app\models\ContactForm;
use app\models\LessonForm;


class SiteController extends \app\components\Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new LessonForm();
        
        return $this->render('lesson', [
            'model' => $model,
        ]);
    }

    public function actionCreate()
    {
        $model = new LessonForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['thinkMonitor', 'id' => $model->startKey]);
            $this->render('about');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionThink()
    {
        $model = new LessonForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->set("startKey", $model->startKey);
            Yii::$app->getSession()->set("teacherKey", $model->teacherKey);
            return $this->render('think', [
                'model' => $this->findModel($model->startKey),
            ]);
        } else {
            $this_errors = $model->getErrors();
            Yii::$app->getSession()->setFlash('error_save', print_r($this_errors, true));
            Yii::$app->response->redirect(['site/index']);
        }
    }


    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    protected function findModel($id)
    {
        if (($model = LessonForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
