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
use app\models\Student;
use app\models\Task;
use app\models\Answer;
use yii\web\UploadedFile;
use app\components\ResultsDisplay;

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
    public function actionLesson_quick()
    {

        $this->layout = 'teacher';
        $model = new Lesson();
        
        return $this->render('lesson_quick', [
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
                
                if($fileTempName==""){
                    $model = new LessonUpload();        
                    return $this->render('lesson_upload', [
                        'model' => $model,
                    ]);
                }
                
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
                                if(!isset($model->taskTypes[$key])){
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
                        if(isset($config['general']['description'])){
                            $this_description = str_replace("<br/>", "\r\n", $config['general']['description']);
                            $model->description = $this_description;
                        }
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
 

        $lesson = Lesson::find()->where(
            ['startKey'=>Yii::$app->getSession()->get("startKey")]
            )->one();
            
        if(is_null($lesson)){return $this->render('error', ["msg" => "lesson not found"]);}
        
        $lesson->thinkingMinutes = $this->transformThinkingMinutes($request_params["Lesson"]["thinkingMinutes"]);
        $lesson->poll_type = $request_params["Lesson"]["poll_type"];
        
        if(!$lesson->save()){
            echo "error 'saving lesson in actionTeacher_poll_codes.'".$lesson->thinkingMinutes;
            if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($lesson->getErrors());}
            exit;
        }
    
        /** 
        There are two types of team polls:
        
            "team":     A template_teacher is created and teachers use the activationkey of the template teacher
                        When the activationkey is used a new teacher is created and the teacher-user gets the prompt to add their name (optional)
    
            "names":    The initiator provides a list of teacher-names that take part in the poll.
                        The initiator-teacher and every teacher in the list is created with the name and an individual activationkey.
                        Also a template_teacher is created to provide the opportunity that more teachers can join during the process,
                        ... or to provide a second chance for those who lost their private keys :-)
        */
    
    
    /**
        if($lesson->poll_type == "single"){
            $single_teacher = new Teacher();
            $single_teacher->startKey = $lesson->startKey;
            $single_teacher->name = $request_params["Teacher"]["name"];
            $single_teacher->status = "initiator";
            $single_teacher->state = "prepared";
            $single_teacher->activationkey = $single_teacher->generateUniqueRandomString("activationkey", 8);
            $single_teacher->studentkey = $single_teacher->generateUniqueRandomString("studentkey", 8);
            $single_teacher->resultkey = $single_teacher->generateUniqueRandomString("resultkey", 8);
            if(!$single_teacher->save()){
                if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($single_teacher->getErrors());}
                die("error 'saving single_teacher in actionTeacher_poll_codes.'");
            }
        }
    */
        
        $teachers_existing = false;
    //if($lesson->poll_type == "team" | $lesson->poll_type == "names"){
        $template_teacher = Teacher::find()->where(
                    ['startKey'=>Yii::$app->getSession()->get("startKey")
                    , "status" => "template"]
                    )->one();
        if($template_teacher === null){
            $template_teacher = new Teacher();
            $template_teacher->startKey = $lesson->startKey;
            $template_teacher->name = "template_do_not_display";
            $template_teacher->status = "template";
            $template_teacher->state = "prepared";
            $template_teacher->activationkey = $template_teacher->generateUniqueRandomString("activationkey", 8);
            $template_teacher->studentkey = $template_teacher->generateUniqueRandomString("studentkey", 8);
            $template_teacher->resultkey = $template_teacher->generateUniqueRandomString("resultkey", 8);
            if(!$template_teacher->save()){
                die("error 'saving template_teacher in actionTeacher_poll_codes.'");
                if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($template_teacher->getErrors());}
            }
        }else{
            $teachers_existing = true;
        }
    //}
        
        if(!$teachers_existing & $lesson->poll_type == "names"){
            
            
            foreach($teachers_collected as $this_teacher => $val){
                $teacher = new Teacher();
                $teacher->startKey = $lesson->startKey;
                $teacher->name = $this_teacher;
                $teacher->status = "teacher";
                $teacher->state = "prepared";
                if(!$teacher->save()){
                    if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($teacher->getErrors());}
                    die("error 'saving teacher from names list in actionTeacher_poll_codes.'");
                }
            }
        }
        
        $teachers = array();
        if($lesson->poll_type == "names"){
            $teacher_rows = Teacher::find()->where(['startKey'=>Yii::$app->getSession()->get("startKey")])->all();
            foreach($teacher_rows as $row){
                //if($row->status == "initiator"){$single_teacher = $row;}
                if($row->status == "template"){$template_teacher = $row;}
                $teachers[$row["name"]] = $row->toArray();
            }
            ksort($teachers);
        }

        $this->layout = 'teacher';
        
        if($lesson->poll_type == "names"){
            return $this->render('teacher_poll_codes', [
                'lesson' => $lesson,
                //'initiator' => $single_teacher,
                'template_teacher' => $template_teacher,
                'teachers' => $teachers,
            ]);
        }
        elseif($lesson->poll_type == "team"){
            return $this->render('teacher_poll_code_single', [
                'lesson' => $lesson,
                'template_teacher' => $template_teacher,
            ]);
        }elseif($lesson->poll_type == "single"){
            return $this->render('teacher_join_poll', [
                'lesson' => $lesson,
                'teacher' => $single_teacher,
            ]);
        }
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
        
        $teacher = new Teacher();
        $lesson = false;
        
        /** when an activationkey of an pepared names list or general team activationkey has been entered */
        if($request_params = Yii::$app->request->get()){
            $request_params = $request_params["Teacher"];
            $teacher = Teacher::find()->where(['activationkey'=>$request_params["activationkey"]])->one();
            $lesson = Lesson::find()->where(['startKey'=>$teacher->startKey])->one();
            if($teacher === null){
                Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('teacher_join_poll_activation_error'));
                return Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            }

            /** still active or has time run out */
            $deadline = new \DateTime($lesson->insert_timestamp);
            $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
            if(new \DateTime() >= $deadline){
                $msg_deadline = Yii::$app->_L->get('teacher_join_poll_login_error_time');
                $msg_deadline = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline, 'dd. MMM').'</b>', $msg_deadline);
                $msg_deadline = str_replace('#lesson-title#', '"<b>'.$lesson->title.'</b>"', $msg_deadline);                    
                Yii::$app->getSession()->setFlash('login_error', $msg_deadline);
                return Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            }

            if($teacher->state != "prepared" & $teacher->status != "template"){
                Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('teacher_join_poll_activation_error_used_key'));
                return Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            }
            $teacher->state = "active";
            
        /** when a poll has been prepared and is started as an immediate single poll */
        }elseif($request_params = Yii::$app->request->post()){
            $lesson = Lesson::find()->where(['startKey' => $request_params["Lesson"]["startKey"]])->one();
            if($lesson === null){
                Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('teacher_join_poll_activation_error'));
                return Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            }
            $lesson->thinkingMinutes = $this->transformThinkingMinutes($request_params["Lesson"]["thinkingMinutes"]);
            $lesson->poll_type = "single";
            if(!$lesson->save()){
                if($_SERVER['HTTP_HOST'] == 'localhost'){var_dump($lesson->getErrors());}
                die("error 'saving lesson in actionTeacher_join_poll.'");
            }
            $teacher->startKey = $lesson->startKey;
            if(isset($request_params["Teacher"])){
                $teacher->name = $request_params["Teacher"]["name"];
            }
            $teacher->status = "teacher";
            $teacher->state = "active";
        }

        if($teacher->status == "template"){
            $teacher = new Teacher();
            $teacher->startKey = $lesson->startKey;
            $teacher->status = "teacher";
            $teacher->state = "active";
            $teacher->validate();
            $teacher->name = $teacher->activationkey;
        }
        
        if(!$teacher->save()){
            if($_SERVER['HTTP_HOST'] == 'localhost'){
                var_dump($teacher->getErrors());
            }
            die("error 'saving teacher... in actionTeacher_join_poll.'");
        }
            
        return $this->render('teacher_join_poll', [
         'teacher' => $teacher,
         'lesson' => $lesson,
        ]);
    }

    /**
     * Displays teacher rejoin running session with starKey and teacherKey
     *
     * @return string
     */
    public function actionTeacher_results()
    {
    
        $countStudentsLimit = 5;
        $request_params = Yii::$app->request->get();
        
        if(isset($request_params["Teacher"])){
            $resultkey = $request_params["Teacher"]["resultkey"];
        }elseif(Yii::$app->getSession()->get("resultkey") != ""){
            $resultkey = Yii::$app->getSession()->get("resultkey");
        }else{
            Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            return;
        }
        
        $teacher = Teacher::find()->where(['resultkey'=>$resultkey])->one();
        if($teacher == null){
            Yii::$app->getSession()->setFlash('login_error', Yii::$app->_L->get('teacher_join_poll_resultkey_error'));
            Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            return;
        }
        $lesson = Lesson::find()->where(['startKey'=>$teacher->startKey])->one();
        
        /** still active or has time run out */
        $deadline = new \DateTime($lesson->insert_timestamp);
        $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
        $deadline->modify('+2 week');
        if(new \DateTime() >= $deadline){
            $msg_deadline = Yii::$app->_L->get('teacher_join_poll_results_error_time');
            $msg_deadline = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline, 'dd. MMM').'</b>', $msg_deadline);
            $msg_deadline = str_replace('#lesson-title#', '"<b>'.$lesson->title.'</b>"', $msg_deadline);                    
            Yii::$app->getSession()->setFlash('login_error', $msg_deadline);
            Yii::$app->response->redirect(['site/lesson_exact?lesson_type=poll&show_teacher_join']);
            return;
        }
        
        Yii::$app->getSession()->set("resultkey", $teacher->resultkey);
        Yii::$app->getSession()->set("startKey", $lesson->startKey);
        
        $teachers = Teacher::find()->where(['startKey'=>$teacher->startKey])->all();
        //$students = Student::find()->where(['startKey'=>$teacher->startKey, 'status'=>'finished'])->all();
        $students = Student::find()->where(['startKey'=>$teacher->startKey])->all();
        $tasks = Task::find()->where(['startKey'=>$teacher->startKey])->all();
        $answers = Answer::find()->where(['startKey'=>$teacher->startKey])->all();
        
        
        
        $teachersArr = array("countAll"=>count($teachers));
        $teachersArr["countActive"] = 0;
        $teachersArr["withStudents"] = 0;
        $teachersArr["students"] = array();
        
        foreach($teachers as $this_teacher){
            if($this_teacher["name"] == "template_do_not_display"){continue;}
            if($this_teacher->state == "active"){$teachersArr["countActive"]++;}
            $this_teacher_name = "";
            if($this_teacher->name != $this_teacher->activationkey){
                $this_teacher_name = $this_teacher->name;
            }
            $teachersArr["students"][$this_teacher->id] = array("name"=>$this_teacher_name,
                                                                "countStudents"=>0,
                                                                "state"=>$this_teacher->state,
                                                                );
        }
        $myStudentsIds = array();
        $studentIds = array();
        $numStudents = array("all"=>count($students));
        $numStudents["mine"] = 0;
        foreach($students as $this_student){
            $studentIds[$this_student->id] = "";
            if(isset($teachersArr["students"][$this_student->teacher_id])){
                $teachersArr["students"][$this_student->teacher_id]["countStudents"]++;
            }
            if($this_student->teacher_id == $teacher->id){
                $myStudentsIds[$this_student->id] = "";
                $numStudents["mine"]++;
            }
        }
        
        foreach($teachersArr["students"] as $this_teacher_id => $val_arr){
            if($val_arr["countStudents"] >= $countStudentsLimit){$teachersArr["withStudents"]++;}
        }
        

        $taskAnswers = array();
        $taskTypes_used = array();
        foreach($tasks as $this_task){
            if(!isset($taskTypes_used[$this_task->type])){
                $taskTypes_used[$this_task->type] = 0;
            }
            $taskTypes_used[$this_task->type]++;
            if(!isset($lesson->taskTypes[$this_task->type])){continue;}
            $taskAnswers[$this_task->taskId] = $this_task->toArray();
            $taskAnswers[$this_task->taskId]["countAnswers"] = 0;
            $taskAnswers[$this_task->taskId]["countNumericAnswers"] = 0;
            $taskAnswers[$this_task->taskId]["sumAnswers"] = 0;
            $taskAnswers[$this_task->taskId]["distribution"] = array();
            $taskAnswers[$this_task->taskId]["my_countAnswers"] = 0;
            $taskAnswers[$this_task->taskId]["my_countNumericAnswers"] = 0;
            $taskAnswers[$this_task->taskId]["my_sumAnswers"] = 0;
            $taskAnswers[$this_task->taskId]["my_distribution"] = array();
            $taskAnswers[$this_task->taskId]["my_textAnswers"] = array();
        }

        foreach($answers as $this_answer){
           
            /** only answers from student who have finished the poll */
            if(!isset($studentIds[$this_answer->studentId])){continue;}
                
            if(trim($this_answer->answer_text)==""){continue;}
            
            if(!isset($taskAnswers[$this_answer->taskId])){continue;}
            
            if(!isset($taskAnswers[$this_answer->taskId]["type"])){continue;}
            
            if(!isset($lesson->taskTypes[$taskAnswers[$this_answer->taskId]["type"]])){continue;}
            
            
            $this_answer_type = $lesson->taskTypes[$taskAnswers[$this_answer->taskId]["type"]]["type"];
            
            $isMyStudent = false;
            if(isset($myStudentsIds[$this_answer->studentId])){
                $isMyStudent = true;
            }

            if($this_answer_type == "scale"){
                $taskAnswers[$this_answer->taskId]["countAnswers"] += 1;
                if(is_numeric($this_answer->answer_text)){
                    $taskAnswers[$this_answer->taskId]["sumAnswers"] += intval($this_answer->answer_text);
                    $taskAnswers[$this_answer->taskId]["countNumericAnswers"] += 1;
                    if(!isset($taskAnswers[$this_answer->taskId]["distribution"][$this_answer->answer_text])){
                        $taskAnswers[$this_answer->taskId]["distribution"][$this_answer->answer_text] = 1;
                    }else{
                        $taskAnswers[$this_answer->taskId]["distribution"][$this_answer->answer_text] += 1;
                    }
                }
                if($isMyStudent){
                    $taskAnswers[$this_answer->taskId]["my_countAnswers"] += 1;
                    if(is_numeric($this_answer->answer_text)){
                        $taskAnswers[$this_answer->taskId]["my_sumAnswers"] += intval($this_answer->answer_text);
                        $taskAnswers[$this_answer->taskId]["my_countNumericAnswers"] += 1;
                        if(!isset($taskAnswers[$this_answer->taskId]["my_distribution"][$this_answer->answer_text])){
                            $taskAnswers[$this_answer->taskId]["my_distribution"][$this_answer->answer_text] = 1;
                        }else{
                            $taskAnswers[$this_answer->taskId]["my_distribution"][$this_answer->answer_text] += 1;
                        }
                    }
                }
            }
            
             if($isMyStudent & $this_answer_type=="string"
                ){
                    $taskAnswers[$this_answer->taskId]["my_textAnswers"][$this_answer->studentId] = $this_answer->answer_text;
             }
        }

        foreach($taskAnswers as $key => $task){
            if($lesson->taskTypes[$task["type"]]["type"]!="scale"){continue;}
            $val_arr_my = array();
            $val_arr_all = array();
            $val_arr_my_percent = array();
            $val_arr_all_percent = array();
            foreach($lesson->taskTypes[$task["type"]]["values"] as $task_type => $task_type_val){
                if(!is_numeric($task_type_val)){continue;}
                
                if(isset($task["my_distribution"][$task_type_val])){
                   $val = $task["my_distribution"][$task_type_val];
                   $val_arr_my[$task_type_val] = $val;
                   $val_arr_my_percent[$task_type_val] = (100*round($val/$task["my_countNumericAnswers"], 2));
                }else{
                   $val_arr_my[$task_type_val] = "0";
                   $val_arr_my_percent[$task_type_val] = "0";
                }
                
                if(isset($task["distribution"][$task_type_val])){
                   $val = $task["distribution"][$task_type_val];
                   $val_arr_all[$task_type_val] = $val;
                   $val_arr_all_percent[$task_type_val] = (100*round($val/$task["countNumericAnswers"], 2));
                }else{
                   $val_arr_all[$task_type_val] = "0";
                   $val_arr_all_percent[$task_type_val] = "0";
                }
            }
            $taskAnswers[$key]["val_arr_my"] = $val_arr_my;
            $taskAnswers[$key]["val_arr_all"] = $val_arr_all;
            $taskAnswers[$key]["val_arr_my_percent"] = $val_arr_my_percent;
            $taskAnswers[$key]["val_arr_all_percent"] = $val_arr_all_percent;
        }


        $render_arr = [
         'lesson' => $lesson,
         'teacher' => $teacher,
         'teachersArr' => $teachersArr,
         'numStudents' => $numStudents,
         'taskAnswers' => $taskAnswers,
         'countStudentsLimit' => $countStudentsLimit,
         'taskTypes_used' => $taskTypes_used,
        ];


        if(isset($request_params["print"])){
            $render_arr["print"] = $request_params["print"];
            $print_how = "printer";
            if(isset($request_params["print"])){
                $print_how = $request_params["how"];
            }
            if($print_how == "pdf"){
                require_once(\Yii::$app->basePath.'/vendor/autoload.php');
                
                $filename = preg_replace("[^A-Za-z0-9_-öäüÖÄÜß]", "_", $lesson->title);
                $filename .= "_".date("Y-m-d");
                
                $mpdf = new \mPDF();
                $mpdf->SetTitle($filename);
                $mpdf->WriteHTML($this->renderPartial('teacher_poll_results_print', $render_arr));

                $mpdf->Output($filename.".pdf", "I");
                exit;
            }else{
                return $this->renderPartial('teacher_poll_results_print', $render_arr);
            }
        }
        
        return $this->render('teacher_poll_results', $render_arr);
        
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
        $content .= "\r\ndescription = ".str_replace("\r\n", "<br/>", $lesson["description"]);
        $content .= "\r\ntype = ".$lesson["type"];
        $content .= "\r\n\r\n[tasks]";
        
        $tasks = Task::findAll(['startKey' => Yii::$app->getSession()->get("startKey")]);
        foreach($tasks as $task){
            if($task["type"] == "sysinfo"){continue;}
            $content .= "\r\n". $task["type"]." = ".$task["task_text"];
        }
        
        /** utf8 byt order marker! */
        $content = chr(239) . chr(187) . chr(191) . $content;
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
        
        $this_filename = Yii::$app->_L->get('gen_activationcodes');
        $this_filename .= "_".date("Y-m-d");
        $this_filename .= "_".$title_clean;
        $this_filename .= ".csv";

        $content = "name;activationcode";
        $teachers = Teacher::findAll(['startKey' => Yii::$app->getSession()->get("startKey")]);
        foreach($teachers as $teacher){
            if($teacher["name"] == "template_do_not_display"){
                $teacher["name"] = Yii::$app->_L->get('teacher_poll_codes_template_activationkey');
            }
            $content .= "\r\n".$teacher["name"].";".$teacher["activationkey"];
        }
        /** utf8 byt order marker! */
        $content = chr(239) . chr(187) . chr(191) . $content;
        Yii::$app->response->sendContentAsFile($content, $this_filename)->send();
        return;
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout_poll()
    {
        return $this->render('about_poll');
    }

    public function actionAbout_lesson()
    {
        return $this->render('about_lesson');
    }


    public function actionThink()
    {
        $model = new Lesson();

        $request = Yii::$app->request;
            //var_dump($request->post());
            //die("test");
        
        if ($request->isGet)  {

            $request_params = $request->get("Lesson");
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

            $request_params = $request->post();
            
            $this_view_error = 'lesson';
            if(isset($request_params["type"])){
                if($request_params["type"] == 'poll'){
                    $this_view_error = 'poll_exact';
                }
            }
            $poll_type = "single";
            if(isset($request_params["poll_type"])){
                if($request_params["poll_type"] == 'team'){
                    $poll_type = 'team';
                }
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
                        if($poll_type == "team" | $poll_type == "names"){
                            $this_view = 'poll_team';
                            $model->thinkingMinutes = 'end_of_next_week';
                        }else{
                            $this_view = 'poll_single';
                            $model->thinkingMinutes = 'today';
                        }
                    }

                    return $this->render($this_view, [
                        'model' => $model,
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
    
    protected function transformThinkingMinutes($tM){
        if(!is_numeric($tM)){
            $dtNow = date_create();
            $remainingDaysOfWeek = 6 - $dtNow->format("w");
            $beginOfDay = clone $dtNow;
            $endOfDay = clone $beginOfDay;
            $endOfDay->modify('tomorrow');
            $minutesLeftToday = ceil(($endOfDay->getTimeStamp() - $dtNow->getTimeStamp())/60) - 1;
            switch($tM){
                case "today":
                    $tM = $minutesLeftToday;
                    break;
                case "end_of_this_week":
                    $tM = ($remainingDaysOfWeek * 24 * 60) + $minutesLeftToday;
                    break;
                case "end_of_next_week":
                    $tM = ((7 + $remainingDaysOfWeek) * 24 * 60) + $minutesLeftToday;
                    break;
                case "end_of_week_after_next":
                    $tM = ((14 + $remainingDaysOfWeek) * 24 * 60) + $minutesLeftToday;
                    break;
                case "end_of_week_and_90_days":
                    $tM = ($remainingDaysOfWeek * 24 * 60) + (90  * 24 * 60) + $minutesLeftToday;
                    break;
            }
        }
        return $tM;
    }

    
}
