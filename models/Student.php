<?php

namespace app\models;

use Yii;

/**
if(!function_exists("_L")){include_once(\Yii::$app->basePath.'\language\language.php');}
*/

/**
 * This is the model class for table "student".
 *
 * @property integer $id
 * @property string $startKey
 * @property string $teacher_id
 * @property string $name
 * @property string $studentKey
 * @property string $remote_ip
 * @property integer $position
 * @property string $stats
 * @property string $status
 * @property string $lastchange
 * @property string $insert_timestamp
 *
 * @property Answer[] $answers
 * @property Task[] $tasks
 * @property Lesson $startKey0
 */
class Student extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student';
    }

    /** teacherName is retrieved by the rest service using the teacher_id field in the student tabel */
    
    public $teacherName = "";


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startKey', 'name', 'studentKey'], 'required'],
            [['position'], 'integer'],
            [['teacher_id'], 'integer'],
            [['stats', 'status', 'remote_ip'], 'string'],
            [['lastchange', 'insert_timestamp'], 'safe'],
            [['startKey', 'studentKey'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 100],
            [['startKey', 'name'], 'unique', 'targetAttribute' => ['startKey', 'name'], 'message' => 'The combination of Start Key and Name has already been taken.'],
            [['startKey'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::className(), 'targetAttribute' => ['startKey' => 'startKey']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'startKey' => '',
            'teacher_id' => '',
            'name' => '',
            'studentKey' => '',
            'remote_ip' => '',
            'position' => '',
            'stats' => '',
            'status' => '',
            'lastchange' => '',
            'insert_timestamp' => '',
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if($this->status == ""){
                $this->status = "empty";
            }
            if(isset($_SERVER["REMOTE_ADDR"])){$this->remote_ip = $_SERVER["REMOTE_ADDR"];}
            if($this->studentKey == ""){
                $this->studentKey = "s-".$this->generateUniqueRandomString("studentKey", 8);
            }
            if($this->name == ""){
                $this->name = $this->generateUniqueRandomString("name", 10);
            }
            return true;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['studentId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['taskId' => 'taskId'])->viaTable('answer', ['studentId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStartKey0()
    {
        return $this->hasOne(Lesson::className(), ['startKey' => 'startKey']);
    }
}
