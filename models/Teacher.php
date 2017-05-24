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
 * @property string $status
 * @property string $activationkey
 * @property string $studentkey
 * @property integer $resultkey
 * @property integer $state
 *
 * @property Lesson $startKey0
 */
class Teacher extends \app\components\ActiveRecord
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
            [['startKey', 'name', 'studentkey', 'resultkey', 'activationkey', 'status', 'state'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['resultkey'], 'string', 'max' => 12],
            [['startKey'], 'string', 'max' => 12],
            [['email'], 'string', 'max' => 150],
            [['activationkey'], 'string', 'max' => 12],
            ['state', 'in', "range" => ['prepared', 'active', 'finished']],
            ['status', 'in', "range" => ['initiator', 'teacher', 'template']],
            [['studentkey'], 'string', 'max' => 50],
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
            'startKey' => '',
            'id' => '',
            'name' => '',
            'email' => '',
            'status' => '',
            'studentkey' => '',
            'resultkey' => '',
            'activationkey' => '',
            'state' => '',
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
