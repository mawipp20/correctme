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
        $this->layout = 'teacher';
        $model = new Lesson();
        $model_upload = new LessonUpload();
        $uploadedTasks = array();
        $fileTempName = "";


        /** set lesson_type to 'lesson' or 'poll' */
        
        $lesson_type = 'lesson';
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
                        if(isset($config['general']['type'])){$model->title = $config['general']['type'];}
                    }
                    if(isset($config['tasks'])){
                        $uploadedTasks = $config['tasks'];
                    }
                }
        }
        
        return $this->render($lesson_type.'_exact', [
            'model' => $model,
            'uploadedTasks' => $uploadedTasks,
            'fileTempName' => $fileTempName,
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
     * Displays teacher rejoin running session with starKey and teacherKey
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
    public function actionDownload_questions()
    {
        $umlaute = array(
            "/Ö/" => "Oe",
            "/ö/" => "oe",
            "/Ü/" => "Ue",
            "/ü/" => "ue",
            "/Ä/" => "Ae",
            "/ä/" => "ae",
            "/ß/" => "ss",
            "/ /" => "-",
        );
        $lesson = $this->findLesson(Yii::$app->getSession()->get("startKey"));
        
        $title_clean = preg_replace(array_keys($umlaute), array_values($umlaute), $lesson->title);
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
                            //Yii::$app->getSession()->setFlash('error_save', print_r($new_tasks, true));
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
