<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\DynamicModel;
use app\models\Lesson;
use app\models\LessonUpload;
use yii\web\UploadedFile;

//use app\models\StudentJoinForm;
//use app\models\StudentForm;
//var_dump(Yii::$app->_L->get('error_server_connect'));

//die(print_r(Yii::$app->getComponents()));

//die(Yii::$app->language->get("error_server_connect"));

//Yii::$app->message->display('I am Yii2.0 Programmer');

//var_dump(Yii::$app->request);
//var_dump(Yii::$app->message);
//var_dump(Yii::$app->_L->get("error_server_connect"));
//die();

//die();

/**
if(!function_exists("_L")){
    include_once(\Yii::$app->basePath.'\language\language.php');
}
*/
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

        if (Yii::$app->request->isPost) {
            
                $model_upload->lessonFile = UploadedFile::getInstance($model_upload, 'lessonFile');
                $fileTempName = $model_upload->lessonFile->tempName;
                //$model_upload->fileParsed = parse_ini_file($fileTempName, true);
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
                                    $this_flash = Yii::$app->_L->get("lesson_upload_task_check_error");
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
                    $this_flash = Yii::$app->_L->get("lesson_upload_task_check_success");
                    $this_flash = str_replace("#number#", ($taskCount - $taskErrorCount), $this_flash);
                    Yii::$app->getSession()->setFlash('success_task_'.$taskCount, $this_flash);
                    $model_upload->fileParsed = $parsedArr;                    
                    fclose($handle);
                } else {
                    Yii::$app->getSession()->setFlash('error_file_read', Yii::$app->_L->get('lesson_upload_file_read_error'));
                }

                if (!is_null($model_upload->fileParsed)) {
                    $config = $model_upload->fileParsed;
                    if(isset($config['general'])){
                        if(isset($config['general']['numTeamsize'])){$model->numTeamsize = $config['general']['numTeamsize'];}
                        if(isset($config['general']['thinkingMinutes'])){$model->thinkingMinutes = $config['general']['thinkingMinutes'];}
                        if(isset($config['general']['earlyPairing'])){$model->earlyPairing = $config['general']['earlyPairing'];}
                        if(isset($config['general']['namedPairing'])){$model->namedPairing = $config['general']['namedPairing'];}
                    }
                    if(isset($config['tasks'])){
                        $uploadedTasks = $config['tasks'];
                    }
                }
        }
        
        return $this->render('lesson_exact', [
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
     * Displays teacher rejoin running session with starKey and teacherKey
     *
     * @return string
     */
    public function actionSession_rejoin()
    {
        $model = new Lesson();

        //$model_teacherKey_validate = new DynamicModel(compact('teacherKey'));
        //$model_teacherKey_validate->addRule('teacherKey', 'required', ['message' => Yii::$app->_L->get('lesson_input_required_message')]);
        
        return $this->render('session_rejoin', [
             'model' => $model,
            //,'model_teacherKey_validate' => $model_teacherKey_validate
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
            if ($model->load($request->post()) && $model->save()) {
                    Yii::$app->getSession()->set("startKey", $model->startKey);
                    Yii::$app->getSession()->set("teacherKey", $model->teacherKey);
                    
                    $post = Yii::$app->request->post();
                    $new_tasks = array();
                    if(isset($post["new_tasks"])){
                        $new_tasks = json_decode(Yii::$app->request->post("new_tasks"));
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
                    
                    return $this->render('think', [
                        'model' => $this->findLesson($model->startKey),
                    ]);
                } else {
                    $this_errors = $model->getErrors();
                    Yii::$app->getSession()->setFlash('error_save', print_r($this_errors, true));
                    Yii::$app->response->redirect(['site/lesson']);
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

}
