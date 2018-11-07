<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;


use app\assets\AppAsset;
AppAsset::register($this);

$this->title = Yii::$app->_L->get("student_join_".Yii::$app->params["cmPollOrLesson"]."_title");

?>

<h3 style="margin-top: 0.0em; margin-bottom: 1em;"><?= Yii::$app->_L->get('student_join_'.Yii::$app->params["cmPollOrLesson"].'_title') ?></h3>

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

          <?= Html::hiddenInput('type', Yii::$app->params["cmPollOrLesson"])  ?>

          <?= $form->field($lesson, 'startKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-student-join' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            //'placeholder'=>Yii::$app->_L->get('student_join_".Yii::$app->params["cmPollOrLesson"]."_startKey_placeholder')
            'value' => $lesson->startKey,
            'autofocus' => 'true',
            ])
            ->label(Yii::$app->_L->get('student_join_lesson_student_label'))
            ; ?>

          <?= $form->field($student, 'name'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-student-join' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('student_join_name_placeholder'),
            'value' => $student->name,
            'autofocus' => 'true',
            ])
            ->label(Yii::$app->_L->get('student_join_name_label'))
            ; ?>

        <div class="form-group" style="margin-top: 1em;">
            <?= Html::submitButton(Yii::$app->_L->get('student_join_lesson_btn_submit'),
             ['class' => 'btn btn-primary'
             , 'id'=>'student_join_btn_submit'
             , 'onclick' => 'cm_spinner();'
             ]) ?>
        </div>


    <?php
        Modal::begin([
            'header' => Yii::$app->_L->get('gent_to_start'),
            'toggleButton' => [ 'style' => 'display:none;','id' => 'modal_spinner'],
            'size' => "modal-sm"
        ]);
        echo '<div id="modalContent">';
        echo '<p style="text-align:center">';
        echo '<i style="font-size:24pt" class="fa fa-spinner fa-spin" aria-hidden="true"></i>';
        echo '<p/></div>';
        Modal::end();
    ?>



    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>
