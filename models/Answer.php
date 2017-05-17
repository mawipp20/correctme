<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property string $startKey
 * @property integer $studentId
 * @property integer $taskId
 * @property string $answer_text
 * @property string $status
 *
 * @property Student $student
 * @property Task $task
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startKey', 'studentId', 'taskId', 'answer_text'], 'required'],
            [['studentId', 'taskId'], 'integer'],
            [['answer_text', 'status'], 'string'],
            [['startKey'], 'string', 'max' => 12],
            [['studentId'], 'exist', 'skipOnError' => true, 'targetClass' => Student::className(), 'targetAttribute' => ['studentId' => 'id']],
            [['taskId'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['taskId' => 'taskId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'startKey' => 'Start Key',
            'studentId' => 'Student ID',
            'taskId' => 'Task ID',
            'answer_text' => 'Answer Text',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'studentId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['taskId' => 'taskId']);
    }
}
