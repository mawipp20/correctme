<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 */

/**
if(!function_exists("_L")){include_once(\Yii::$app->basePath.'\language\language.php');}
*/

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
            [['startKey'], 'required', 'message' => Yii::$app->_L->get('student_join_key_required_message')],
            [['name'], 'required', 'message' => Yii::$app->_L->get('student_join_name_required_message')],
            [['startKey'], 'string', 'min' => 6, 'message' => Yii::$app->_L->get('student_join_key_required_message')],
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
            'teacher_id' => '',
        ];
    }
}
