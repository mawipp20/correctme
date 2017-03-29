<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property string $startKey
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $studentkey
 * @property integer $resultkey
 *
 * @property Lesson $startKey0
 */
class Teacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['startKey', 'name', 'studentkey', 'resultkey'], 'required'],
            [['name'], 'string'],
            [['resultkey'], 'string', 'max' => 12],
            [['startKey'], 'string', 'max' => 12],
            [['email'], 'string', 'max' => 150],
            [['studentkey'], 'string', 'max' => 50],
            [['startKey', 'email'], 'unique', 'targetAttribute' => ['startKey', 'email'], 'message' => 'The combination of Start Key and Email has already been taken.'],
            [['startKey'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::className(), 'targetAttribute' => ['startKey' => 'startKey']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'startKey' => '',
            'id' => '',
            'name' => '',
            'email' => '',
            'studentkey' => '',
            'resultkey' => '',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStartKey0()
    {
        return $this->hasOne(Lesson::className(), ['startKey' => 'startKey']);
    }
}
