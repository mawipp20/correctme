<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

$this->title = Yii::$app->_L->get("student_join_lesson_title");

?>

<h3 style="margin-top: 0.0em; margin-bottom: 1em;"><?= Yii::$app->_L->get('student_join_lesson_title') ?></h3>


    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
        
    ?>

<div class="Lesson">

    <?php $form = ActiveForm::begin([
                'action' => ['think'],
                'method' => "post",    
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
    ]); ?>

          <?= Html::hiddenInput('type', 'poll')  ?>

          <?= $form->field($model, 'startKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-student-join' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('student_join_lesson_startKey_placeholder')
            , 'value' => $model->startKey
            , 'autofocus' => 'true'
            ,
            ])
            ->label(Yii::$app->_L->get('student_join_lesson_startKey_label'))
            ; ?>

          <?= $form->field($model, 'name'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-student-join' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('student_join_name_placeholder')
            , 'value' => $model->name
            , 'autofocus' => 'true'
            ,
            ])
            ->label(Yii::$app->_L->get('student_join_name_label'))
            ; ?>


        <div class="form-group" style="margin-top: 1em;">
            <?= Html::submitButton(Yii::$app->_L->get('student_join_btn_submit'), ['class' => 'btn btn-primary', 'id'=>'student_join_btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
