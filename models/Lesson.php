<?php

namespace app\models;

use Yii;
use app\models\Language;

/**
 * This is the model class for table "lesson".
 *
 * @property string $startKey
 * @property string $teacherKey
 * @property integer $teacherId
 * @property integer $title
 * @property integer $type
 * @property integer $numTasks
 * @property integer $numStudents
 * @property integer $numTeamsize
 * @property integer thinkingMinutes
 * @property string $typeTasks
 * @property integer $earlyPairing
 * @property string $typeMixing
 * @property integer $namedPairing
 * @property integer poll_type
 */

/**
if(!function_exists("_L")){
    include_once(\Yii::$app->basePath.'\language\language.php');
}
*/

class Lesson extends \app\components\ActiveRecord

{
    /**
     * @inheritdoc
     */
     
    public $lessonFile; 
    public $taskTypes = array(  "text"=>array("type" => "string"),
                                "how-true"=>array(
                                        "type" => "numeric",
                                        "max_value" => 4,
                                        "values" => array("x"=>"x"
                                                  ,"1"=>1
                                                  ,"2"=>2
                                                  ,"3"=>3
                                                  ,"4"=>4
                                                  ),
                                        "background-colors" => array("x"=>"rgb(255,255,255)"
                                                  ,"1"=>"rgb(150,150,255)"
                                                  ,"2"=>"rgb(70,70,255)"
                                                  ,"3"=>"rgb(0,0,200)"
                                                  ,"4"=>"rgb(0,0,60)"
                                                  ),
                                        "font-colors" => array("x"=>"black"
                                                  ,"1"=>"black"
                                                  ,"2"=>"black"
                                                  ,"3"=>"white"
                                                  ,"4"=>"white"
                                                  ),
                                        ),
                                "how-often"=>array(
                                        "type" => "numeric",
                                        "max_value" => 4,
                                        "values" => array("x"=>"x"
                                                  ,"1"=>1
                                                  ,"2"=>2
                                                  ,"3"=>3
                                                  ,"4"=>4
                                                  ),
                                        "background-colors" => array("x"=>"rgb(255,255,255)"
                                                  ,"1"=>"rgb(200,200,255)"
                                                  ,"2"=>"rgb(150,150,255)"
                                                  ,"3"=>"rgb(0,0,170)"
                                                  ,"4"=>"rgb(0,0,60)"
                                                  ),
                                        "font-colors" => array("x"=>"black"
                                                  ,"1"=>"black"
                                                  ,"2"=>"black"
                                                  ,"3"=>"white"
                                                  ,"4"=>"white"
                                                  ),
                                        ),
                                "sysinfo"=>array("type" => "info"),
                                "info"=>array("type" => "info"),
                                ); 
    
    
    public static function tableName()
    {
        return 'lesson';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['type', 'in', 'range' => ['lesson', 'poll']],
            [['title'], 'string', 'max' => 30, 'message' => Yii::$app->_L->get('lesson_title_max_length')],

            [['numTasks'], 'required', 'message' => Yii::$app->_L->get('numTasks_required')],
            [['numTasks'], 'integer', 'min'=>1, 'max'=>100, 'message' => '1 - 100'],
            
            [['thinkingMinutes'], 'required', 'message' => Yii::$app->_L->get('thinkingMinutes_required')],
            [['thinkingMinutes'], 'integer', 'min'=>2, 'max'=>30240, 'message' => '2 - 30240'],

            [['numTeamsize'], 'required', 'message' => Yii::$app->_L->get('numTeamsize_required')],
            [['numTeamsize'], 'integer', 'min'=>2, 'max'=>6, 'message' => '2 - 6'],

            [['numStudents'], 'required', 'message' => Yii::$app->_L->get('numStudents_required')],
            [['numStudents'], 'integer', 'min'=>2, 'max'=>50, 'message' => '2 - 50'],

            [['lessonFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'ini'],
            
            ['poll_type', 'in', 'range' => ['single', 'team', 'names']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'startKey' => '',
            'teacherKey' => '',
            'teacherId' => '',
            'type' => '',
            'title' => '',
            'numTasks' => '',
            'numStudents' => '',
            'numTeamsize' => '',
            'thinkingMinutes' => '',
            'typeTasks' => '',
            'earlyPairing' => Yii::$app->_L->get('LABEL_earlyPairing'),
            'typeMixing' => Yii::$app->_L->get('LABEL_typeMixing'),
            'namedPairing' => Yii::$app->_L->get('LABEL_namedPairing'),
            'poll_type' => '',
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if($this->startKey == ""){
                $this->startKey = $this->generateUniqueRandomString("startKey", 6);
            }
            if($this->teacherKey == ""){
                $this->teacherKey = $this->generateUniqueRandomString("teacherKey", 6);
            }
            
            if($this->type == ""){
                $this->type = 'lesson';
            }
            if($this->typeTasks == ""){
                $this->typeTasks = 'textarea';
            }
            if($this->type == "poll"){
                $this->numStudents = 2;
                $this->numTeamsize = 2;
                if($this->thinkingMinutes == 0){
                    $this->thinkingMinutes = 60;
                }
            }
            return true;
        }
    }
    
    public function beforeSave($insert)
    {
        
        if (parent::beforeSave($insert)) {
            if($this->isNewRecord)
            {
                //$this->startKey = $this->generateUniqueRandomString("startKey", 6);
            }
            return true;
        } else {
            return false;
        }
    }
    /**
    public function upload_lesson()
    {

        if ($this->validate()) {
            //$this->lessonFile->saveAs('uploads/' . $this->lessonFile->baseName . '.' . $this->lessonFile->extension);
            return true;
        } else {
            return false;
        }
    }
    */
}
