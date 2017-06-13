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
            $teacher->name = $request_params["Teacher"]["name"];
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
        
        $teachers = Teacher::find()->where(['startKey'=>$teacher->startKey])->all();
        $students = Student::find()->where(['startKey'=>$teacher->startKey, 'status'=>'finished'])->all();
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
            $teachersArr["students"][$this_teacher->id] = array("name"=>$this_teacher->name, "countStudents"=>0, "state"=>$this_teacher->state);
        }
        $myStudentsIds = array();
        $numStudents = array("all"=>count($students));
        $numStudents["mine"] = 0;
        foreach($students as $this_student){
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
        foreach($tasks as $this_task){
            if(!isset($lesson->taskTypes[$this_task->type])){continue;}
            if($lesson->taskTypes[$this_task->type]==""){continue;}
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
            
            if(trim($this_answer->answer_text)==""){continue;}
            
            if(!isset($taskAnswers[$this_answer->taskId])){continue;}
            if(!isset($taskAnswers[$this_answer->taskId]["type"])){continue;}
            
            if(!isset($lesson->taskTypes[$taskAnswers[$this_answer->taskId]["type"]])){continue;}
            
            $this_answer_type = $lesson->taskTypes[$taskAnswers[$this_answer->taskId]["type"]];
            
            $isMyStudent = false;
            if(isset($myStudentsIds[$this_answer->studentId])){
                $isMyStudent = true;
            }

            if(is_array($this_answer_type)){
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
                    $taskAnswers[$this_answer->taskId]["my_textAnswers"][] = $this_answer->answer_text;
             }
        }

        $render_arr = [
         'lesson' => $lesson,
         'teacher' => $teacher,
         'teachersArr' => $teachersArr,
         'numStudents' => $numStudents,
         'taskAnswers' => $taskAnswers,
         'countStudentsLimit' => $countStudentsLimit
        ];


        if(isset($request_params["print"])){
            return $this->teacher_result_pdf($render_arr, $request_params["print"]);
        }
        
        return $this->render('teacher_poll_results', $render_arr);
        
    }

    private function teacher_result_pdf($arr, $print){
        
        $t = "<style>\n".file_get_contents(\Yii::$app->basePath.'/web/css/teacher_poll_result_print.css')."\n</style>\n";
        if($print == "my"){
            foreach($arr["taskAnswers"] as $task){
                $t .= "\n<div class='task'>\n";
                $t .= '<div class="task_text">'.$task["task_text"];
                $t .= "<span class='num_answers'>(".$task["my_countNumericAnswers"].")</span></div>\n";
                $t .= ResultsDisplay::get_distribution($arr["lesson"], $task, "my_");
                $t .= "\n</div>\n";
            }
        }
        
        //echo $t;
        //exit;
        
        require_once(\Yii::$app->basePath.'/vendor/tecnick.com/tcpdf/tcpdf.php');
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

/**
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Martin Wippersteg');
        $pdf->SetTitle('Befragungsergebnisse');
        $pdf->SetSubject('Befragungsergebnisse');
        $pdf->SetKeywords('correctme, Befragung');
        
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 061', PDF_HEADER_STRING);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // set font
        $pdf->SetFont('helvetica', '', 10);
*/        

$html = '
<!-- EXAMPLE OF CSS STYLE -->
<style>
    h1 {
        color: navy;
        font-family: times;
        font-size: 24pt;
        text-decoration: underline;
    }
    p.first {
        color: #003300;
        font-family: helvetica;
        font-size: 12pt;
    }
    p.first span {
        color: #006600;
        font-style: italic;
    }
    p#second {
        color: rgb(00,63,127);
        font-family: times;
        font-size: 12pt;
        text-align: justify;
    }
    p#second > span {
        background-color: #FFFFAA;
    }
    table.first {
        color: #003300;
        font-family: helvetica;
        font-size: 8pt;
        border-left: 3px solid red;
        border-right: 3px solid #FF00FF;
        border-top: 3px solid green;
        border-bottom: 3px solid blue;
        background-color: #ccffcc;
    }
    td {
        border: 2px solid blue;
        background-color: #ffffee;
    }
    td.second {
        border: 2px dashed green;
    }
    div.test {
        color: #CC0000;
        background-color: #FFFF66;
        font-family: helvetica;
        font-size: 10pt;
        border-style: solid solid solid solid;
        border-width: 2px 2px 2px 2px;
        border-color: green #FF00FF blue red;
        text-align: center;
    }
    .lowercase {
        text-transform: lowercase;
    }
    .uppercase {
        text-transform: uppercase;
    }
    .capitalize {
        text-transform: capitalize;
    }
</style>

<h1 class="title">Example of <i style="color:#990000">XHTML + CSS</i></h1>

<p class="first">Example of paragraph with class selector. <span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sed imperdiet lectus. Phasellus quis velit velit, non condimentum quam. Sed neque urna, ultrices ac volutpat vel, laoreet vitae augue. Sed vel velit erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras eget velit nulla, eu sagittis elit. Nunc ac arcu est, in lobortis tellus. Praesent condimentum rhoncus sodales. In hac habitasse platea dictumst. Proin porta eros pharetra enim tincidunt dignissim nec vel dolor. Cras sapien elit, ornare ac dignissim eu, ultricies ac eros. Maecenas augue magna, ultrices a congue in, mollis eu nulla. Nunc venenatis massa at est eleifend faucibus. Vivamus sed risus lectus, nec interdum nunc.</span></p>

<p id="second">Example of paragraph with ID selector. <span>Fusce et felis vitae diam lobortis sollicitudin. Aenean tincidunt accumsan nisi, id vehicula quam laoreet elementum. Phasellus egestas interdum erat, et viverra ipsum ultricies ac. Praesent sagittis augue at augue volutpat eleifend. Cras nec orci neque. Mauris bibendum posuere blandit. Donec feugiat mollis dui sit amet pellentesque. Sed a enim justo. Donec tincidunt, nisl eget elementum aliquam, odio ipsum ultrices quam, eu porttitor ligula urna at lorem. Donec varius, eros et convallis laoreet, ligula tellus consequat felis, ut ornare metus tellus sodales velit. Duis sed diam ante. Ut rutrum malesuada massa, vitae consectetur ipsum rhoncus sed. Suspendisse potenti. Pellentesque a congue massa.</span></p>

<div class="test">example of DIV with border and fill.
<br />Lorem ipsum dolor sit amet, consectetur adipiscing elit.
<br /><span class="lowercase">text-transform <b>LOWERCASE</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
<br /><span class="uppercase">text-transform <b>uppercase</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
<br /><span class="capitalize">text-transform <b>cAPITALIZE</b> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</span>
</div>

<br />

<table class="first" cellpadding="4" cellspacing="6">
 <tr>
  <td width="30" align="center"><b>No.</b></td>
  <td width="140" align="center" bgcolor="#FFFF00"><b>XXXX</b></td>
  <td width="140" align="center"><b>XXXX</b></td>
  <td width="80" align="center"> <b>XXXX</b></td>
  <td width="80" align="center"><b>XXXX</b></td>
  <td width="45" align="center"><b>XXXX</b></td>
 </tr>
 <tr>
  <td width="30" align="center">1.</td>
  <td width="140" rowspan="6" class="second">XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="140">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="30" align="center" rowspan="3">2.</td>
  <td width="140" rowspan="3">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="80">XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="80" rowspan="2" >XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr>
  <td width="30" align="center">3.</td>
  <td width="140">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
 <tr bgcolor="#FFFF80">
  <td width="30" align="center">4.</td>
  <td width="140" bgcolor="#00CC00" color="#FFFF00">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td width="80">XXXX<br />XXXX</td>
  <td align="center" width="45">XXXX<br />XXXX</td>
 </tr>
</table>
';

$html = '
<style>
.task{
    margin-bottom: 3em;
    margin-top: 3em;
}
.task .first{
    margin-top: 1.5em;
}
.task_text{
    margin-bottom: 0.5em;
}
.num_answers{
    color: grey;
    padding-left: 30px;
}
.one_distribution_quota{
    font-weight: bold;
    border: 1px solid black;
    width: 50px;
}
table.first{
    background-color: yellow;
}
</style>

<table class="first" cellpadding="4" cellspacing="0">
 <tr>
  <td width="10%" align="center"><b>No.</b></td>
  <td width="20%" align="center" bgcolor="#FFFF00"><b>XXXX</b></td>
  <td width="20%" align="center"><b>XXXX</b></td>
  <td width="50%" align="center" style="border: 1px solid black;"><b>XXXX</b></td>
 </tr>
</table>

<div class="task">
<div class="task_text">Ich habe die gestellten Aufgaben  erledigt.<span class="num_answers">(3)</span></div>
<div class="one_distribution_quota">3</div>
<div class="one_distribution" style="color:white; background-color: rgb(0,30,0);"><span class="one_distribution_val">1</span></div><div class="one_distribution" style="color:white; background-color: rgb(0,30,0);"><span class="one_distribution_val">1</span></div><div class="one_distribution" style="color:white; background-color: rgb(0,30,0);"><span class="one_distribution_val">1</span></div>
</div>
</div>
';

        // add a page
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        $pdf->Output('filename.pdf', 'I');
        Yii::app()->end();                    
        
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
    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionThink()
    {
        $model = new Lesson();

        $request = Yii::$app->request;
        
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
            
            //var_dump($request_params);
            //exit;
            

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
            
            
            //var_dump($request->post());
            //exit;
            
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
                        //'model' => $this->findLesson($model->startKey),
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
            }
        }
        return $tM;
    }

    
}
