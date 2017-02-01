<?php

namespace app\models;

use Yii;

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

if(!function_exists("_L")){
    include_once(\Yii::$app->basePath.'\language\language.php');
}


class LessonForm extends \app\components\ActiveRecord

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
            [['numTasks', 'numTeamsize', 'thinkingMinutes', 'numStudents', 'startKey'], 'required', 'message' => _L('lesson_input_required_message')],
            [['numTasks'], 'integer', 'min'=>1, 'max'=>10],
            [['numStudents'], 'integer', 'min'=>2, 'max'=>50],
            [['numTeamsize'], 'integer', 'min'=>2, 'max'=>6],
            [['thinkingMinutes'], 'integer', 'min'=>2, 'max'=>600],
            [['startKey'], 'string', 'max' => 12, 'min' => 6],
            [['typeTasks', 'typeMixing'], 'string', 'max' => 20],
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
            'numTasks' => '',
            'numStudents' => '',
            'numTeamsize' => '',
            'thinkingMinutes' => '',
            'typeTasks' => '',
            'earlyPairing' => _L('LABEL_earlyPairing'),
            'typeMixing' => _L('LABEL_typeMixing'),
            'namedPairing' => _L('LABEL_namedPairing'),
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
