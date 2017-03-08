<?php

/**
 * @author Martin Wippersteg
 * @copyright 2017
 */

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class LessonUpload extends Model
{
    /**
     * @var UploadedFile
     */
     
    public $lessonFile;
    public $fileParsed;

    public function rules()
    {
        return [
            [['lessonFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'ini'],
        ];
    }
    
    public function upload()
    {

        if ($this->validate()) {
            //$this->lessonFile->saveAs('uploads/' . $this->lessonFile->baseName . '.' . $this->lessonFile->extension);
            return true;
        } else {
            return false;
        }
    }
}

?>