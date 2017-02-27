<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

$this->title = _L("lesson_title");

/* @var $this yii\web\View */
/* @var $model app\models\Lesson */
/* @var $form ActiveForm */
?>


<h3 style="margin-top: 0em; margin-bottom: 20px;"><?= _L('LESSON_WELCOME') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

<ul class="nav nav-tabs" style="margin-bottom: 20px;">
  <li class="active"><a href="#"><?= _L('lesson_nav_tab_quick') ?></a></li>
  <li><a href="lesson_exact"><?= _L('lesson_nav_tab_exact') ?></a></li>
  <li><a href="lesson_upload"><?= _L('lesson_nav_tab_upload') ?></a></li>
</ul>

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                'action' => ['think'],
                'method' => "post", 
                'id' => 'lesson_form',
    ]); ?>

          <?= $form->field($model, 'numTasks'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('numTasks_placeholder')
            , 'value' => $model->numTasks
            , 'autofocus' => 'true'
            ,
            ])
            ->label(_L('numTasks_label'))
            ; ?>


          <?= $form->field($model, 'thinkingMinutes'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('thinkingMinutes_placeholder')
            , 'value' => $model->thinkingMinutes
            ,
            ])
            ->label(_L('thinkingMinutes_label'))
            ; ?>

          <?= $form->field($model, 'numTeamsize'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('numTeamsize_placeholder')
            , 'value' => $model->numTeamsize
            ,
            ])
            ->label(_L('numTeamsize_label'))
            ; ?>

          <?= $form->field($model, 'numStudents'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('numStudents_placeholder')
            , 'value' => $model->numStudents
            ,
            ])
            ->label(_L('numStudents_label'))
            ; ?>

            
        <!--
        <div class="btn-group input-group-lesson">
          <button type="button" class="btn btn-default" onclick='btnGroupToggle(this, "Lesson-typetasks", "text")'>
          <?php //echo _L('typeTasks_label_short') ?></button>
          <button type="button" class="btn btn-success" onclick='btnGroupToggle(this, "Lesson-typetasks", "textarea")'>
          <?php // echo _L('typeTasks_label_long') ?></button>
          <input id="Lesson-typetasks" name="Lesson[typeTasks]" value="textarea" type="hidden">
        </div>        
        -->

        <?php //echo $form->field($model, 'earlyPairing') ?>
        <?php //echo $form->field($model, 'namedPairing') ?>
        <?php //echo $form->field($model, 'typeTasks') ?>
        <?php //echo $form->field($model, 'typeMixing') ?>
        <?php /** echo $form->field($model, 'namedPairing')->checkbox(array( 
                                                'labelOptions'=>array('style'=>'padding:5px;'), 
                                                'disabled'=>true 
                                                ));
            */
        ?>


        <div class="form-group" style="margin-top: 1em;">
            <?php //echo Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            <?php //echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::submitButton(_L('lesson_btn_submit'), ['class' => 'btn btn-primary', 'id'=>'lesson_btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(_L('_L_lesson')); ?>;</script>

<!-- Lesson -->
