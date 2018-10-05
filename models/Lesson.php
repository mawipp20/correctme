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
    public $taskTypesOrder = array(   "lesson"=>array("text","","how-true","","info")
                                    , "poll"=>array("how-true","how-often","plus-minus","text","info")
                                    ); 
    public $taskTypes = array(  "text"=>array("type" => "string"),
                                "how-true"=>array(
                                        "type" => "scale",
                                        "max_value" => 4,
                                        "gap_after" => "3",
                                        "values" => array(
                                                  "4"=>4,
                                                  "3"=>3,
                                                  "2"=>2,
                                                  "1"=>1,
                                                  "x"=>"x",
                                                  ),
                                        "symbols" => array(
                                                  "1"=>"&#x2212;&#x2212;",
                                                  "2"=>"&#x2212;",
                                                  "3"=>"+",
                                                  "4"=>"++",
                                                  "x"=>"  ",
                                                  ),
                                        "background-colors" => array(
                                                  "1"=>"rgb(240,240,240)",
                                                  "2"=>"rgb(210,210,210)",
                                                  "3"=>"rgb(100,100,200)",
                                                  "4"=>"rgb(0,0,60)",
                                                  "x"=>"rgb(255,255,255)",
                                                  ),
                                        "background-colors-print" => array(
                                                  "1"=>"rgb(190,190,190)",
                                                  "2"=>"rgb(230,230,230)",
                                                  "3"=>"rgb(230,230,230)",
                                                  "4"=>"rgb(190,190,190)",
                                                  "x"=>"rgb(255,255,255)",
                                                  ),
                                        "font-colors" => array(
                                                  "1"=>"black",
                                                  "2"=>"black",
                                                  "3"=>"white",
                                                  "4"=>"white",
                                                  "x"=>"black",
                                                  ),
                                        "background-images" => array(
                                                  "1"=>"minus_minus_black.gif",
                                                  "2"=>"minus_black.gif",
                                                  "3"=>"plus.gif",
                                                  "4"=>"plus_plus.gif",
                                                  "x"=>"",
                                                  ),
                                        "background-images-print" => array(
                                                  "1"=>"minus_minus_print.gif",
                                                  "2"=>"minus_black.gif",
                                                  "3"=>"plus.gif",
                                                  "4"=>"plus_plus.gif",
                                                  "x"=>"",
                                                  ),
                                        "pie_percentages" => array(
                                                  "1"=>0,
                                                  "2"=>25,
                                                  "3"=>75,
                                                  "4"=>100,
                                                  "x"=>"",
                                                  ),
                                        ),
                                "how-often"=>array(
                                        "type" => "scale",
                                        "max_value" => 4,
                                        "gap_after" => "3",
                                        "values" => array(
                                                  "4"=>4,
                                                  "3"=>3,
                                                  "2"=>2,
                                                  "1"=>1,
                                                  "x"=>"x",
                                                  ),
                                        "symbols" => array(
                                                  "1"=>"&#x2212;&#x2212;",
                                                  "2"=>"&#x2212;",
                                                  "3"=>"+",
                                                  "4"=>"++",
                                                  "x"=>"  ",
                                                  ),
                                        "background-colors" => array(
                                                  "1"=>"rgb(240,240,240)",
                                                  "2"=>"rgb(210,210,210)",
                                                  "3"=>"rgb(100,100,200)",
                                                  "4"=>"rgb(0,0,60)",
                                                  "x"=>"rgb(255,255,255)",
                                                  ),
                                        "background-colors-print" => array(
                                                  "1"=>"rgb(190,190,190)",
                                                  "2"=>"rgb(230,230,230)",
                                                  "3"=>"rgb(230,230,230)",
                                                  "4"=>"rgb(190,190,190)",
                                                  "x"=>"rgb(255,255,255)",
                                                  ),
                                        "background-images-print" => array(
                                                  "1"=>"minus_minus_print.gif",
                                                  "2"=>"minus_black.gif",
                                                  "3"=>"plus.gif",
                                                  "4"=>"plus_plus.gif",
                                                  "x"=>"",
                                                  ),
                                        "font-colors" => array(
                                                  "1"=>"black",
                                                  "2"=>"black",
                                                  "3"=>"white",
                                                  "4"=>"white",
                                                  "x"=>"black",
                                                  ),
                                        "background-images" => array(
                                                  "1"=>"minus_minus_black.gif",
                                                  "2"=>"minus_black.gif",
                                                  "3"=>"plus.gif",
                                                  "4"=>"plus_plus.gif",
                                                  "x"=>"",
                                                  ),
                                        "pie_percentages" => array(
                                                  "1"=>0,
                                                  "2"=>25,
                                                  "3"=>75,
                                                  "4"=>100,
                                                  "x"=>"",
                                                  ),
                                        ),
                                "plus-minus"=>array(
                                        "type" => "scale",
                                        "max_value" => 4,
                                        "gap_after" => "3",
                                        "values" => array(
                                                  "4"=>4,
                                                  "3"=>3,
                                                  "2"=>2,
                                                  "1"=>1,
                                                  "x"=>"x",
                                                  ),
                                        "symbols" => array(
                                                  "1"=>"&#x2212;&#x2212;",
                                                  "2"=>"&#x2212;",
                                                  "3"=>"+",
                                                  "4"=>"++",
                                                  "x"=>"  ",
                                                  ),
                                        "background-colors" => array(
                                                  "1"=>"rgb(240,240,240)",
                                                  "2"=>"rgb(210,210,210)",
                                                  "3"=>"rgb(100,100,200)",
                                                  "4"=>"rgb(0,0,60)",
                                                  "x"=>"rgb(255,255,255)",
                                                  ),
                                        "background-colors-print" => array(
                                                  "1"=>"rgb(190,190,190)",
                                                  "2"=>"rgb(230,230,230)",
                                                  "3"=>"rgb(230,230,230)",
                                                  "4"=>"rgb(190,190,190)",
                                                  "x"=>"rgb(255,255,255)",
                                                  ),
                                        "background-images-print" => array(
                                                  "1"=>"minus_minus_print.gif",
                                                  "2"=>"minus_black.gif",
                                                  "3"=>"plus.gif",
                                                  "4"=>"plus_plus.gif",
                                                  "x"=>"",
                                                  ),
                                        "font-colors" => array(
                                                  "1"=>"black",
                                                  "2"=>"black",
                                                  "3"=>"white",
                                                  "4"=>"white",
                                                  "x"=>"black",
                                                  ),
                                        "background-images" => array(
                                                  "1"=>"minus_minus_black.gif",
                                                  "2"=>"minus_black.gif",
                                                  "3"=>"plus.gif",
                                                  "4"=>"plus_plus.gif",
                                                  "x"=>"",
                                                  ),
                                        "pie_percentages" => array(
                                                  "1"=>"",
                                                  "2"=>"",
                                                  "3"=>"",
                                                  "4"=>"",
                                                  "x"=>"",
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
            [['description'], 'string', 'max' => 1000, 'message' => Yii::$app->_L->get('lesson_description_max_length')],

            [['numTasks'], 'required', 'message' => Yii::$app->_L->get('numTasks_required')],
            [['numTasks'], 'integer', 'min'=>1, 'max'=>100, 'message' => '1 - 100'],
            
            [['thinkingMinutes'], 'required', 'message' => Yii::$app->_L->get('thinkingMinutes_required')],
            [['thinkingMinutes'], 'integer', 'min'=>2, 'max'=>172800, 'message' => '2 - 172800'],

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
            'description' => '',
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
            
            if($this->type == "lesson" & $this->thinkingMinutes == ""){
                $this->thinkingMinutes = 11520;
            }
            
            if($this->typeTasks == ""){
                $this->typeTasks = 'text';
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

/**
    
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
*/    
    
    
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
