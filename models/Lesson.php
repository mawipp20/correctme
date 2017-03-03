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
 * @property integer $numTasks
 * @property integer $numStudents
 * @property integer $numTeamsize
 * @property integer thinkingMinutes
 * @property string $typeTasks
 * @property integer $earlyPairing
 * @property string $typeMixing
 * @property integer $namedPairing
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

            [['numTasks'], 'required', 'message' => Yii::$app->_L->get('numTasks_required')],
            [['numTasks'], 'integer', 'min'=>1, 'max'=>20, 'message' => '1 - 20'],
            
            [['thinkingMinutes'], 'required', 'message' => Yii::$app->_L->get('thinkingMinutes_required')],
            [['thinkingMinutes'], 'integer', 'min'=>2, 'max'=>600, 'message' => '2 - 600'],

            [['numTeamsize'], 'required', 'message' => Yii::$app->_L->get('numTeamsize_required')],
            [['numTeamsize'], 'integer', 'min'=>2, 'max'=>6, 'message' => '2 - 6'],

            [['numStudents'], 'required', 'message' => Yii::$app->_L->get('numStudents_required')],
            [['numStudents'], 'integer', 'min'=>2, 'max'=>50, 'message' => '2 - 50'],

            //[['startKey'], 'string', 'max' => 12, 'min' => 6],
            //[['typeTasks', 'typeMixing'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        global $_L;
        return [
            'startKey' => '',
            'teacherKey' => '',
            'teacherId' => '',
            'numTasks' => '',
            'numStudents' => '',
            'numTeamsize' => '',
            'thinkingMinutes' => '',
            'typeTasks' => '',
            'earlyPairing' => Yii::$app->_L->get('LABEL_earlyPairing'),
            'typeMixing' => Yii::$app->_L->get('LABEL_typeMixing'),
            'namedPairing' => Yii::$app->_L->get('LABEL_namedPairing'),
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
            if($this->typeTasks == ""){
                $this->typeTasks = 'textarea';
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
}
