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

if(!function_exists("_L")){include_once(\Yii::$app->basePath.'\language\language.php');}


class StudentJoinForm extends \app\components\ActiveRecord

{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startKey'], 'required', 'message' => _L('student_join_key_required_message')],
            [['name'], 'required', 'message' => _L('student_join_name_required_message')],
            [['startKey'], 'string', 'min' => 6, 'message' => _L('student_join_key_required_message')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'startKey' => '',
            'name' => '',
        ];
    }
}
