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
use app\models\Task;
use app\models\Answer;

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
    public function actionStudent_join_lesson()
    {
/**        $model = new StudentJoinForm();
        return $this->render('student_join_lesson', [
            'model' => $model,
        ]);
*/
        $lesson = new Lesson();
        $student = new Student();
        return $this->render('student_join_lesson', [
            'lesson' => $lesson
            ,'student' => $student
        ]);

    }

    /**
     * Cancels the session without saving
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
     * Saves the tasks the session without saving
     *
     * @return string
     */
    public function actionCommit_single()
    {
        
        $student = Student::find()->where(
            ['studentkey' => Yii::$app->getSession()->get("studentKey")
            ,'startKey' => Yii::$app->getSession()->get("startKey")]
            )->one();
        if(is_null($student)){
            Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('student_join_lesson_rejoin_error'));
            Yii::$app->response->redirect(['student/student_join_lesson']);
            return;
        }
        $student->status = "finished";
        $student->save();

        $request_params = Yii::$app->request->get();

        $lesson = Lesson::find()->where(['startKey'=>$student->startKey])->one();
               
        $tasks = Task::find()->where(['startKey'=>$student->startKey])->orderBy('num')->all();
        $answers = Answer::find()->where(['startKey'=>$student->startKey])->all();
        $answers_taskIds = array();
        foreach($answers as $this_answer){
            $answers_taskIds[$this_answer["taskId"]] = $this_answer;
        }
        $tasks_answers = array();
        for($i = 0; $i < count($tasks); $i++){
            $tasks_answers[$i] = $tasks[$i]->toArray();
            $this_answer = $answers_taskIds[$tasks[$i]["taskId"]]->toArray();
            $this_type = $tasks_answers[$i]["type"];
            if(isset($lesson->taskTypes[$this_type])){
                if($lesson->taskTypes[$this_type]["type"]=="scale"){
                    $this_answer["answer_text"] = Yii::$app->_L->get('scale_'.$this_type.'-'.$this_answer["answer_text"]);
                }
            }
            $tasks_answers[$i]["answer"] = $this_answer;
        }
        
        $render_arr = [
            'student' => $student,
            'lesson' => $lesson,
            'tasks_answers' => $tasks_answers,
            'print' => false
            ];

        //$debug_pdf = true;
        //if($debug_pdf){            
        if(isset($request_params["print"])){
            //if($debug_pdf){
            if($request_params["print"] == "pdf"){
                $render_arr["print"] = true;
                
                require_once(\Yii::$app->basePath.'/vendor/autoload.php');
                
                $filename = preg_replace("[^A-Za-z0-9_-öäüÖÄÜß]", "_", $lesson->description);
                if(strlen($filename)>30){$filename = substr($filename,0,30);}
                
                $filename .= "_".date("Y-m-d");
                
                $mpdf = new \mPDF();
                $mpdf->SetTitle($filename);
                $mpdf->WriteHTML($this->renderPartial('student_think_finished', $render_arr));
    
                $mpdf->Output($filename.".pdf", "I");
                exit;
            }
        }

        
        return $this->render('student_think_finished', $render_arr);
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
                
        $student = new Student();
        $this->layout = 'student';

        $request = Yii::$app->request;


        /** create new student */
        
        if ($request->isPost) {
            
            $post = $request->post();
            
            $error_page = 'student/student_join_poll';
            if($post["type"] == "lesson"){$error_page = 'student/student_join_lesson';}
            
            /** from student_join_poll: get the startKey via studentkey of the teacher */
                       
            if(isset($post["Teacher"])){
                $post["StudentJoinForm"] = array("startKey"=>"", "name"=>Yii::$app->getSecurity()->generateRandomString(32));
                $teacher = Teacher::find()->where(['studentkey' => $post["Teacher"]["studentkey"]])->one();
                
                if(!is_null($teacher)){
                    $post["StudentJoinForm"]["startKey"] = $teacher->startKey;
                    $post["StudentJoinForm"]["teacher_id"] = $teacher->id;
                    
                    $student->startKey = $teacher->startKey;
                    $student->teacher_id = $teacher->id;
                    $lesson = Lesson::find()->where(['startKey' => $student->startKey])->one();
                    
                    
                }else{
                    /** check if student tried a lesson key as a poll key */
                    $check_lesson = Lesson::find()->where(['startKey' => $post["Teacher"]["studentkey"]
                                                            ,'type' => $post["type"]])->one();
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
            
            /** from student_join_lesson: join collaborative lesson with startKey of the lesson */
            
            if(isset($post["Lesson"]) & isset($post["Student"])){
                    
                    $startKey = trim($post["Lesson"]["startKey"]);
                    
                    $name = trim($post["Student"]["name"]);
                    
                    $lesson = Lesson::find()->where(['startKey' => $startKey])->one();
    
                    if(is_null($lesson)){
                        /** to rejoin a lesson the student can enter the personal access key
                         *  that he has noted and which also the teacher can give him
                         */
                        $student = Student::find()->where(['studentKey' => $startKey])->one();
                        if(!is_null($student)){
                            $startKey = $student->startKey;
                            Yii::$app->getSession()->set("studentKey", $student->studentKey);
                            Yii::$app->getSession()->set("startKey", $startKey);
                            $lesson = Lesson::find()->where(['startKey' => $startKey])->one();
                        } 
                        
                    }
                    
                    if(is_null($lesson)){    
                        Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('student_join_login_error'));
                        Yii::$app->response->redirect([$error_page]);
                        return;
                    }
                    /** still active or has time run out */
                    if($lesson->thinkingMinutes > 0){
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
                    }
                    
                    if($student->startKey == ""){
                        $student->startKey = $startKey;
                        $student->name = $name;
                    }
                    
                    $student_with_the_same_name = Student::find()->where(
                                    [    'startKey'=>$student->startKey
                                        ,'name'=>$student->name
                                    ]
                                    )->one();
                    if(!is_null($student_with_the_same_name)){
                                if($_SERVER["REMOTE_ADDR"] == $student_with_the_same_name->remote_ip){
                                    $student = $student_with_the_same_name;    
                                    if($student->status == "finished"){
                                        Yii::$app->response->redirect("commit_single");
                                        return;
                                    }
                                }else{
                                    $student->addErrors(array(Yii::$app->_L->get('student_join_name_already_existing')));    
                                }
                    }
                //}
            }
                        
            /** poll browser sessions that have been concluded can't be repeated by a page refresh or entering the start key again */
            if(Yii::$app->getSession()->get("status") == "finished"
               & $lesson["type"] == "poll"
               & $_SERVER['HTTP_HOST'] != 'localhost'
                ){
                $student->addErrors(array(Yii::$app->_L->get('student_join_error_poll_is_finished')));    
            }
            
            if (!$student->hasErrors() && $student->save()) {

                    Yii::$app->getSession()->set("startKey", trim($student->startKey));
                    Yii::$app->getSession()->set("studentKey", $student->studentKey);
                    Yii::$app->getSession()->set("status", "working");
                    
                    $this->view->params['model'] = $student;
                    $this->view->params['lesson'] = $lesson;
                    
                    return $this->render('student_think', [
                        'model' => $this->findStudent($student->startKey, trim($student->studentKey)),
                        'lesson' => $lesson,
                    ]);
            }
            
            if($student->hasErrors()) {
                    $this_errors = array();
                    foreach($student->getErrors() as $this_error){
                        $this_errors = array_merge($this_errors, $this_error);
                    }
                    Yii::$app->getSession()->setFlash('error_save', implode("<br>", $this_errors));
                    Yii::$app->response->redirect(['student/index']);
            }
        }

        /** let existing students rejoin with studentKey (they got from the teacher) when their session is lost */

        if ($request->isGet)  {
            $request_params = $request->get("Lesson");
            $student->load($request->get());
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
                $this_errors = $student->getErrors();
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
