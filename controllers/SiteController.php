<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;
use app\models\Lesson;
use app\models\LessonUpload;
use app\models\Teacher;
use app\models\Task;
use yii\web\UploadedFile;

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

    public $umlaute = array(
        "/Ö/" => "Oe",
        "/ö/" => "oe",
        "/Ü/" => "Ue",
        "/ü/" => "ue",
        "/Ä/" => "Ae",
        "/ä/" => "ae",
        "/ß/" => "ss",
        "/ /" => "-",
    );



    /**
     * Displays teacher choose poll or collaborate
     *
     * @return string
     */
    public function actionTeacher_poll_or_lesson()
    {

        $this->layout = 'teacher';
        $model = new Lesson();
        
        return $this->render('teacher_poll_or_lesson', [
            'model' => $model,
        ]);
    }

    /**
     * Displays teacher quick lesson create page.
     *
     * @return string
     */
    public function actionLesson()
    {

        $this->layout = 'teacher';
        $model = new Lesson();
        
        return $this->render('lesson', [
            'model' => $model,
        ]);
    }

    /**
     * Displays teacher exact lesson create page.
     *
     * @return string
     */
    public function actionLesson_exact()
    {

        $model = new Lesson();
        $model_upload = new LessonUpload();
        $uploadedTasks = array();
        $fileTempName = "";


        /** set lesson_type to 'lesson' or 'poll' */
        if(!isset($lesson_type)){
            $lesson_type = 'lesson';
        }
        $request_params = array();
        if (Yii::$app->request->isGet) {
            $request_params = Yii::$app->request->get();
        }
        if (Yii::$app->request->isPost) {
            $request_params = Yii::$app->request->post();
            if(isset($request_params["LessonUpload"])){
                $request_params = $request_params["LessonUpload"];
                $request_params["lesson_type"] = $request_params["type"];
            }
        }
        if(isset($request_params["lesson_type"])){
            $lesson_type = $request_params["lesson_type"];
        }
        Yii::$app->getSession()->set("lesson_type", $lesson_type);
        
        /** process uploaded file with tasks/questions */

        if (Yii::$app->request->isPost) {
            
                $model_upload->lessonFile = UploadedFile::getInstance($model_upload, 'lessonFile');
                $fileTempName = $model_upload->lessonFile->tempName;
                $parsedArr = array();
                $currentSection = "";
                $unrecognizedLines = array();
                $taskCount = 0;
                $taskErrorCount = 0;
                $handle = fopen($fileTempName, "r");
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        $line = trim($line);
                        if($line == ""){continue;}
                        if(substr($line, 0, 1) == ";" | substr($line, 0, 1) == "#"){
                            continue;
                        }
                        if(preg_match('/^\[[a-zA-Z_\-0-9]*\]$/', $line)){
                            $currentSection = preg_replace("/\[|\]/", "", $line);
                            $parsedArr[$currentSection] = array();
                            continue;
                        }
                        if($currentSection != ""){
                            $line_arr = explode("=", $line);
                            if(count($line_arr)<=1){
                                $unrecognizedLines[] = $line;
                                continue;
                            }
                            $key = trim($line_arr[0]);
                            $val = trim($line_arr[1]);
                            if(count($line_arr)>2){
                                array_shift($line_arr);
                                $val = implode("=", $line_arr);}
                            if($currentSection != "tasks"){
                                $parsedArr[$currentSection][$key] = $val;                                    
                            }else{
                                $taskCount++;
                                if(!in_array($key, $model->taskTypes)){
                                    $taskErrorCount++;
                                    $this_flash = Yii::$app->_L->get($lesson_type."_upload_task_check_error");
                                    $this_flash = str_replace("#type#", "'".$key."'", $this_flash);
                                    $this_flash = str_replace("#number#", $taskCount + 1, $this_flash);
                                    $this_flash .= " ".$val;
                                    Yii::$app->getSession()->setFlash('warning_task'.$taskCount, $this_flash);
                                }else{
                                    $parsedArr[$currentSection][$val] = $key;                                    
                                }
                            }
                        }
                    }
                    if(count($unrecognizedLines)>0){
                        $this_flash = Yii::$app->_L->get("lesson_upload_unrecognizedLines");
                        $this_flash .= "<br /><br />".implode("<br />", $unrecognizedLines);
                        Yii::$app->getSession()->setFlash('warning_unrecognizedLines', $this_flash);
                    }
                    $this_flash = Yii::$app->_L->get($lesson_type."_upload_task_check_success");
                    $this_flash = str_replace("#number#", ($taskCount - $taskErrorCount), $this_flash);
                    Yii::$app->getSession()->setFlash('success_task_'.$taskCount, $this_flash);
                    $model_upload->fileParsed = $parsedArr;                    
                    fclose($handle);
                } else {
                    Yii::$app->getSession()->setFlash('error_file_read', Yii::$app->_L->get($lesson_type.'_upload_file_read_error'));
                }

                if (!is_null($model_upload->fileParsed)) {
                    $config = $model_upload->fileParsed;
                    if(isset($config['general'])){
                        if(isset($config['general']['numTeamsize'])){$model->numTeamsize = $config['general']['numTeamsize'];}
                        if(isset($config['general']['thinkingMinutes'])){$model->thinkingMinutes = $config['general']['thinkingMinutes'];}
                        if(isset($config['general']['earlyPairing'])){$model->earlyPairing = $config['general']['earlyPairing'];}
                        if(isset($config['general']['namedPairing'])){$model->namedPairing = $config['general']['namedPairing'];}
                        if(isset($config['general']['title'])){$model->title = $config['general']['title'];}
                        if(isset($config['general']['type'])){$model->type = $config['general']['type'];}
                    }
                    if(isset($config['tasks'])){
                        $uploadedTasks = $config['tasks'];
                    }
                }
        }
        
        return $this->render($lesson_type.'_exact', [
            'model' => $model,
            'teacher' => new Teacher(),
            'uploadedTasks' => $uploadedTasks,
            'fileTempName' => $fileTempName,
            'show_teacher_join' => isset($request_params["show_teacher_join"])
        ]);
    }

    /**
     * Displays teacher lesson upload page.
     *
     * @return string
     */
    public function actionLesson_upload()
    {
        $this->layout = 'teacher';
        $model = new LessonUpload();
        
        return $this->render('lesson_upload', [
            'model' => $model,
        ]);
    }

    /**
     * Displays teacher poll upload page.
     *
     * @return string
     */
    public function actionPoll_upload()
    {
        $this->layout = 'teacher';
        $model = new LessonUpload();
        
        return $this->render('poll_upload', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the teacher activationkey for a team poll
     *
     * @return string
     */
    public function actionTeacher_poll_codes()
    {

        $teachers_num_limit = 500;

        $request_params = Yii::$app->request->post();
        
        if(!isset($request_params["teachers_collected"])){return $this->render('error', ["msg" => "lesson_collected_teachers"]);}
        if(!isset($request_params["Teacher"]["name"])){return $this->render('error', ["msg" => "teacher_name"]);}
        if($request_params["Teacher"]["name"]==""){return $this->render('error', ["msg" => "teacher_name empty"]);}
        if(!isset($request_params["Lesson"]["thinkingMinutes"])){return $this->render('error', ["msg" => "lesson_thinkingminutes"]);}
        
        $poll_show_teacher_names = false;
        if(isset($request_params["poll_show_teacher_names"])){
            if($request_params["poll_show_teacher_names"]=="true"){$poll_show_teacher_names = true;}
        }
        

        
        $teachers_collected = array();
        $teachers_collected_pre = explode("#", $request_params["teachers_collected"]);
        foreach($teachers_collected_pre as $this_teacher){
            if($this_teacher == ""){continue;}
            if(mb_strtolower($this_teacher) == mb_strtolower($request_params["Teacher"]["name"])){continue;}
            $teachers_collected[$this_teacher] = "";
        }
        if(count($teachers_collected) > $teachers_num_limit){
            return $this->render('error', ["msg" => "number of teachers limited to ".$teachers_num_limit]);
        }
 
    //   \Yii::$app->db->createCommand()->delete('teacher', 'startkey = "'.Yii::$app->getSession()->get("startKey").'"')->execute();

    $lesson = Lesson::find()->where(
        ['startKey'=>Yii::$app->getSession()->get("startKey")]
        )->one();
        
    if(is_null($lesson)){return $this->render('error', ["msg" => "lesson not found"]);}
    
    $new_thinkingMinutes = $request_params["Lesson"]["thinkingMinutes"];
    if(!is_numeric($new_thinkingMinutes)){
        $dtNow = date_create();
        $remainingDaysOfWeek = 7 - $dtNow->format("w");
        $beginOfDay = clone $dtNow;
        $endOfDay = clone $beginOfDay;
        $endOfDay->modify('tomorrow');
        $minutesLeftToday = ceil(($endOfDay->getTimeStamp() - $dtNow->getTimeStamp())/60);
        switch($new_thinkingMinutes){
            case "today":
                $new_thinkingMinutes = $minutesLeftToday;
                break;
            case "end_of_this_week":
                $new_thinkingMinutes = ($remainingDaysOfWeek * 24 * 60) + $minutesLeftToday;
                break;
            case "end_of_next_week":
                $new_thinkingMinutes = ((7 + $remainingDaysOfWeek) * 24 * 60) + $minutesLeftToday;
                break;
            case "end_of_week_after_next":
                $new_thinkingMinutes = ((14 + $remainingDaysOfWeek) * 24 * 60) + $minutesLeftToday;
                break;
        }
    }

    $lesson->thinkingMinutes = $new_thinkingMinutes;
    $lesson->poll_show_teacher_names = $poll_show_teacher_names;
    if(!$lesson->save()){
        echo "error 'saving lesson in actionTeacher_poll_codes.'";
        if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($lesson->getErrors());}
    }

    $teachers = array();
    $initiator = array();
    
    $teacher_rows = Teacher::find()->where(['startKey'=>Yii::$app->getSession()->get("startKey")])->all();
    if(count($teacher_rows)>0){
        foreach($teacher_rows as $row){
            if($row->status == "initiator"){$initiator = $row;}
            $teachers[$row["name"]] = $row->toArray();
        }
    }else{

        $initiator = new Teacher();
        $initiator->startKey = $lesson->startKey;
        $initiator->name = $request_params["Teacher"]["name"];
        $initiator->status = "initiator";
        $initiator->state = "prepared";
        $initiator->activationkey = $initiator->generateUniqueRandomString("activationkey", 8);
        $initiator->studentkey = $initiator->generateUniqueRandomString("studentkey", 8);
        $initiator->resultkey = $initiator->generateUniqueRandomString("resultkey", 8);
        if(!$initiator->save()){
            echo "error 'saving initiator in actionTeacher_poll_codes.'";
            if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($initiator->getErrors());}
        }
        //Yii::$app->getSession()->set("lessonTitle", $lesson->title);
        //Yii::$app->getSession()->set("activationkey", $initiator->activationkey);
            
        $teachers = array($initiator->name=>$initiator->toArray());
        
        foreach($teachers_collected as $this_teacher => $val){
            $teacher = new Teacher();
            $teacher->startKey = $lesson->startKey;
            $teacher->name = $this_teacher;
            $teacher->status = "teacher";
            $teacher->state = "prepared";
            $teacher->activationkey = $teacher->generateUniqueRandomString("activationkey", 8);
            $teacher->studentkey = $teacher->generateUniqueRandomString("studentkey", 8);
            $teacher->resultkey = $teacher->generateUniqueRandomString("resultkey", 8);
            if(!$teacher->save()){
                echo "error 'saving initiator in actionTeacher_poll_codes.'";
                if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($teacher->getErrors());}
            }
            $teachers[$this_teacher] = $teacher->toArray();
        }
    }
    
    ksort($teachers);
    
        $this->layout = 'teacher';
        
        if(count($teachers_collected) == 0){
            return $this->render('teacher_join_poll', [
                'lesson' => $lesson,
                'teacher' => $initiator,
                'num_teachers' => 1,
            ]);
        }
        return $this->render('teacher_poll_codes', [
            'lesson' => $lesson,
            'initiator' => $initiator,
            'teachers' => $teachers,
        ]);
    }

    /**
     * Displays teacher's results
     *
     * @return string
     */
    public function actionResults()
    {
        $model = new Lesson();

        $request = Yii::$app->request;
        $request_params = $request->get("Lesson");

        if ($request->isGet)  {
            $model->load($request->get());
            $row = Lesson::find()->where(
                    [    'startKey'=>$request_params["startKey"]
                        ,'teacherKey'=>$request_params["teacherKey"]
                    ]
                    )->one();
                    
            if(!is_null($row)){
                return $this->render('think', [
                    'model' => $row,
                ]);
            } else {
                $this_errors = $model->getErrors();
                Yii::$app->getSession()->setFlash('error_save', Yii::$app->_L->get("join_session_login_error_flash"));
                Yii::$app->response->redirect(['site/session_rejoin']);
            }
            
        }

        return $this->render('results', [
             'model' => $model,
        ]);
    }

    /**
     * Displays teacher rejoin running session with starKey and teacherKey
     *
     * @return string
     */
    public function actionSession_rejoin()
    {
        $model = new Lesson();
        return $this->render('session_rejoin', [
             'model' => $model,
        ]);
    }

    /**
     * Displays teacher rejoin running session with starKey and teacherKey
     *
     * @return string
     */
    public function actionTeacher_join_poll()
    {
        $request_params = Yii::$app->request->get();
        $request_params = $request_params["Teacher"];
        $teacher = Teacher::find()->where(['activationkey'=>$request_params["activationkey"]])->one();
        $num_teachers = Teacher::find()->where(['startKey'=>$teacher->startKey])->count();
        //var_dump($teacher);die();
        if($teacher !== null){
                $lesson = Lesson::find()->where(['startKey'=>$teacher->startKey])->one();
                return $this->render('teacher_join_poll', [
                 'teacher' => $teacher,
                 'lesson' => $lesson,
                 'num_teachers' => $num_teachers,
                ]);
        }else{
            Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('teacher_join_poll_login_error'));
            Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
        }
        
    }

    /**
     * download questions before start of the lesson or poll
     *
     * @return string
     */
    public function actionDownload_questions()
    {
        $lesson = $this->findLesson(Yii::$app->getSession()->get("startKey"));
        
        $title_clean = preg_replace(array_keys($this->umlaute), array_values($this->umlaute), $lesson->title);
        $title_clean = preg_replace("/[^A-Za-z0-9_-]/", "", $title_clean);
        
        $this_filename = Yii::$app->_L->get('teacher_questions');
        $this_filename .= "_".date("Y-m-d");
        $this_filename .= "_".$title_clean;
        $this_filename .= ".ini";
        
        $pathSeparator = "/";
        if($_SERVER['HTTP_HOST'] == 'localhost'){$pathSeparator = "\\";}
         $this_path = \Yii::$app->basePath.$pathSeparator.'language'.$pathSeparator;

        $ini_template_file = $this_path."template.questions.".$_SESSION["_LANGUAGE"].".ini";
        if(!is_file($ini_template_file)){
            $ini_template_file = $this_path."template.questions.en.ini";
        }
        $content = file_get_contents($ini_template_file);
        $content .= "\r\n\r\n[general]";
        $content .= "\r\ntitle = ".$lesson["title"];
        $content .= "\r\ntype = ".$lesson["type"];
        $content .= "\r\n\r\n[tasks]\r\n";
        
        $tasks = Task::findAll(['startKey' => Yii::$app->getSession()->get("startKey")]);
        foreach($tasks as $task){
            $content .= "\r\n". $task["type"]." = ".$task["task_text"];
        }
        
        Yii::$app->response->sendContentAsFile($content, $this_filename)->send();
        return;
    }

    /**
     * download questions before start of the lesson or poll
     *
     * @return string
     */
    public function actionDownload_activationcodes()
    {
        $lesson = $this->findLesson(Yii::$app->getSession()->get("startKey"));
        
        $title_clean = preg_replace(array_keys($this->umlaute), array_values($this->umlaute), $lesson->title);
        $title_clean = preg_replace("/[^A-Za-z0-9_-]/", "", $title_clean);
        
        $this_filename = Yii::$app->_L->get('teacher_questions');
        $this_filename .= "_".date("Y-m-d");
        $this_filename .= "_".$title_clean;
        $this_filename .= ".csv";

        $content = "name;activationcode";
        $teachers = Teacher::findAll(['startKey' => Yii::$app->getSession()->get("startKey")]);
        foreach($teachers as $teacher){
            $content .= "\r\n".$teacher["name"].";".$teacher["activationkey"];
        }
        Yii::$app->response->sendContentAsFile($content, $this_filename)->send();
        return;
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


    public function actionThink()
    {
        $model = new Lesson();

        $request = Yii::$app->request;
        $request_params = $request->get("Lesson");

        if ($request->isGet)  {
            $model->load($request->get());
            $row = Lesson::find()->where(
                    [    'startKey'=>$request_params["startKey"]
                        ,'teacherKey'=>$request_params["teacherKey"]
                    ]
                    )->one();
                    
            if(!is_null($row)){
                return $this->render('think', [
                    'model' => $row,
                ]);
            } else {
                $this_errors = $model->getErrors();
                Yii::$app->getSession()->setFlash('error_save', Yii::$app->_L->get("join_session_login_error_flash"));
                Yii::$app->response->redirect(['site/session_rejoin']);
            }
            
        }
        
        if ($request->isPost) {

            $this_view_error = 'lesson';
            if($request_params["type"] == 'poll'){
                $this_view_error = 'poll_exact';
            }
            
            if ($model->load($request->post()) && $model->save()) {
                    Yii::$app->getSession()->set("startKey", $model->startKey);
                    Yii::$app->getSession()->set("teacherKey", $model->teacherKey);
                    
                    $post = Yii::$app->request->post();
                    
                    $new_tasks = array();
                    if(isset($post["new_tasks"])){
                        $new_tasks = json_decode($post["new_tasks"]);
                    }

                    /** create the requested numTasks number of tasks */
                    for($i = 1; $i <= $model->numTasks; $i++){
                        $this_type = $model->typeTasks;
                        $this_text = '';
                        if(isset($new_tasks->$i)){
                            $this_type = $new_tasks->$i->type;
                            $this_text = $new_tasks->$i->task_text;
                        }
                        \Yii::$app->db->createCommand()->insert('task', [
                            'startKey' => $model->startKey,
                            'num' => $i,
                            'type' => $this_type,
                            'task_text' => $this_text,
                        ])->execute();
                    }
                    
                    $this_view = 'think';
                    if($model->type == 'poll'){
                        $this_view = 'teachers';
                    }
                    return $this->render($this_view, [
                        'model' => $this->findLesson($model->startKey),
                        'teacher' => new Teacher(),
                    ]);
                } else {
                    $this_errors = $model->getErrors();
                    Yii::$app->getSession()->setFlash('error_save', print_r($this_errors, true));
                    Yii::$app->response->redirect(['site/'.$this_view_error]);
                }
            }
        }

    protected function findLesson($id)
    {
        if (($model = Lesson::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    
}
