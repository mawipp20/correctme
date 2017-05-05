<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $taskId
 * @property string $startKey
 * @property integer $num
 * @property string $type
 * @property string $text
 *
 * @property Answer[] $answers
 * @property Student[] $students
 * @property Lesson $startKey0
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startKey', 'num', 'type', 'text'], 'required'],
            [['num'], 'integer'],
            [['text'], 'string'],
            [['type'], 'in', 'range' => ['text', 'how-true', 'how-often', 'info'], 'message' => 'Unknown type of task/question'],
            [['startKey'], 'string', 'max' => 12],
            [['startKey', 'num'], 'unique', 'targetAttribute' => ['startKey', 'num'], 'message' => 'The combination of Start Key and Num has already been taken.'],
            [['startKey'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::className(), 'targetAttribute' => ['startKey' => 'startKey']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taskId' => 'Task ID',
            'startKey' => 'Start Key',
            'num' => 'Num',
            'type' => 'Type',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['taskId' => 'taskId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['id' => 'studentId'])->viaTable('answer', ['taskId' => 'taskId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStartKey0()
    {
        return $this->hasOne(Lesson::className(), ['startKey' => 'startKey']);
    }
}
