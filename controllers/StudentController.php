<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\StudentJoinForm;
use app\models\StudentForm;

if(!function_exists("_L")){
    include_once(\Yii::$app->basePath.'\language\language.php');
}

class StudentController extends \app\components\Controller
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
     * Displays the student Login page.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new StudentJoinForm();
        return $this->render('student_join', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the student page for Think - Phase
     *
     * @return string
     */
    public function actionThink()
    {
        $model = new StudentForm();
        

        $request = Yii::$app->request;

        /** create new student */
        
        if ($request->isPost) {            
            if($model->load($request->post(), "StudentJoinForm")){
                if(!is_null(StudentForm::find()->where(
                                [    'startKey'=>$model->startKey
                                    ,'name'=>$model->name
                                ]
                                )->one()
                            )
                        ){
                        $model->addErrors(array(_L('student_join_name_already_existing')));    
                        };
            }
            
            if (!$model->hasErrors() && $model->save()) {
                    Yii::$app->getSession()->set("startKey", $model->startKey);
                    Yii::$app->getSession()->set("studentKey", $model->studentKey);
                    
                    return $this->render('student_think', [
                        'model' => $this->findStudent($model->startKey, $model->studentKey),
                    ]);
            }
            
            if($model->hasErrors()) {
                    $this_errors = array();
                    foreach($model->getErrors() as $this_error){
                        $this_errors = array_merge($this_errors, $this_error);
                    }
                    Yii::$app->getSession()->setFlash('error_save', implode("<br>", $this_errors));
                    Yii::$app->response->redirect(['student/index']);
            }
        }

        /** let existing students rejoin with studentKey (they got from the teacher) when their session is lost */

        if ($request->isGet)  {
            $request_params = $request->get("LessonForm");
            $model->load($request->get());
            $row = LessonForm::find()->where(
                    [    'startKey'=>$request_params["startKey"]
                        ,'studentKey'=>$request_params["studentKey"]
                    ]
                    )->one();
                    
            if(!is_null($row)){
                return $this->render('student_think', [
                    'model' => $row,
                ]);
            } else {
                $this_errors = $model->getErrors();
                Yii::$app->getSession()->setFlash('error_save', _L("join_session_login_error_flash"));
                Yii::$app->response->redirect(['student/student_rejoin']);
            }
            
        }
        


    }



    protected function findStudent($startKey, $studentKey)
    {
        $model = StudentForm::find()->where(
                [    'startKey'=>$startKey
                    ,'studentKey'=>$studentKey
                ]
                )->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}