<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\StudentJoinForm;
use app\models\Student;
use app\models\Teacher;
use app\models\Lesson;
//use yii\tcpdf\TCPDF;

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
        return $this->render('poll_or_lesson', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the student lesson Login page.
     *
     * @return string
     */
    public function actionStudent_join()
    {
        $model = new StudentJoinForm();
        return $this->render('student_join', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the student lesson Login page.
     *
     * @return string
     */
    public function actionCancel()
    {
        Yii::$app->session->destroy();
        $model = new StudentJoinForm();
        return $this->render('poll_or_lesson', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the student poll Login page.
     *
     * @return string
     */
    public function actionStudent_join_poll()
    {
        $teacher = new Teacher();
        return $this->render('student_join_poll', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * Displays the student page for Think - Phase
     *
     * @return string
     */
    public function actionThink()
    {
                
        $model = new Student();
        $this->layout = 'student';

        $request = Yii::$app->request;


        /** create new student */
        
        if ($request->isPost) {
            
            $post = $request->post();
            
            $error_page = 'student/student_join_poll';
            if($post["type"] == "lesson"){$error_page = 'student/student_join';}
            
            /** from student_join_poll: get the startKey via studentkey of the teacher */
            
            if(isset($post["Teacher"])){
                $post["StudentJoinForm"] = array("startKey"=>"", "name"=>Yii::$app->getSecurity()->generateRandomString(32));
                $teacher = Teacher::find()->where(['studentkey' => $post["Teacher"]["studentkey"]])->one();
                
                if(!is_null($teacher)){
                    $post["StudentJoinForm"]["startKey"] = $teacher->startKey;
                    $post["StudentJoinForm"]["teacher_id"] = $teacher->id;
                }else{
                    /** check if student tried a lesson key as a poll key */
                    $check_lesson = Lesson::find()->where(['startKey' => $post["Teacher"]["studentkey"]])->one();
                    if(is_null($check_lesson)){
                        Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('student_join_login_error'));
                        Yii::$app->response->redirect([$error_page]);
                        return;
                    }
                    Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('student_join_poll_login_error_is_lesson'));
                    Yii::$app->response->redirect([$error_page]);
                    return;
                }
            }
            
            /** join collaborative lesson or poll with startKey of the lesson */
            
            if($model->load($post, "StudentJoinForm")){
                $lesson = Lesson::find()->where(['startKey' => $model->startKey])->one();

                /** still active or has time run out */
                $deadline = new \DateTime($lesson->insert_timestamp);
                $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
                if(new \DateTime() >= $deadline){
                    $msg_deadline = Yii::$app->_L->get('student_join_poll_login_error_time');
                    $msg_deadline = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline, 'dd. MMM').'</b>', $msg_deadline);
                    $msg_deadline = str_replace('#lesson-title#', '"<b>'.$lesson->title.'</b>"', $msg_deadline);                    
                    Yii::$app->getSession()->setFlash('login_error', $msg_deadline);
                    Yii::$app->response->redirect([$error_page]);
                    return;
                }
                
                if(is_null($lesson)){
                    Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('student_join_login_error'));
                    Yii::$app->response->redirect([$error_page]);
                    return;
                }
                $student_with_the_same_name = Student::find()->where(
                                [    'startKey'=>$model->startKey
                                    ,'name'=>$model->name
                                ]
                                )->one();
                if(!is_null($student_with_the_same_name)){
                            //$student_with_the_same_name->delete();
                            $model->addErrors(array(Yii::$app->_L->get('student_join_name_already_existing')));    
                }
            }
                        
            /** poll browser sessions that have been concluded can't be repeated by a page refresh or entering the start key again */
            if(Yii::$app->getSession()->get("status") == "finished"
               & $lesson["type"] == "poll"
               & $_SERVER['HTTP_HOST'] != 'localhost'
                ){
                $model->addErrors(array(Yii::$app->_L->get('student_join_error_poll_is_finished')));    
            }
            
            
            
            if (!$model->hasErrors() && $model->save()) {
                    Yii::$app->getSession()->set("startKey", $model->startKey);
                    Yii::$app->getSession()->set("studentKey", $model->studentKey);
                    Yii::$app->getSession()->set("status", "working");
                    
                    $this->view->params['model'] = $model;
                    $this->view->params['lesson'] = $lesson;
                    
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
            $request_params = $request->get("Lesson");
            $model->load($request->get());
            $row = Student::find()->where(
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
                Yii::$app->getSession()->setFlash('error_save', Yii::$app->_L->get("join_session_login_error_flash"));
                Yii::$app->response->redirect(['student/student_rejoin']);
            }
            
        }
        


    }


    public function actionPoll_finished()
    {
        
        //$model = new Student();
        //$this->layout = 'student';

        $request = Yii::$app->request;

        /** set student status to finished */
        
        $model = $this->findStudent( Yii::$app->getSession()->get("startKey")
                             ,Yii::$app->getSession()->get("studentKey")
                            );
        
        Yii::$app->getSession()->set("status", "finished");
                            
        $model->status = "finished";
        if(!$model->save()){
            $this_errors = array();
            foreach($model->getErrors() as $this_error){
                $this_errors = array_merge($this_errors, $this_error);
            }
            Yii::$app->getSession()->setFlash('error_save', implode("<br>", $this_errors));
        }
        
        return $this->render('poll_finished', [
            'model' => $model,
        ]);

                            
    }


    protected function findStudent($startKey, $studentKey)
    {
        $model = Student::find()->where(
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
