<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

$this->title = Yii::$app->_L->get("lesson_title");

/* @var $this yii\web\View */
/* @var $model app\models\Lesson */
/* @var $form ActiveForm */
?>


<h3 style="margin-top: 0em; margin-bottom: 20px;"><?= Yii::$app->_L->get('LESSON_WELCOME') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

<ul class="nav nav-tabs" style="margin-bottom: 20px;">
  <li class="active"><a href="#"><?= Yii::$app->_L->get('lesson_nav_tab_quick') ?></a></li>
  <li><a href="lesson_exact"><?= Yii::$app->_L->get('lesson_nav_tab_exact') ?></a></li>
  <li><a href="lesson_upload"><?= Yii::$app->_L->get('lesson_nav_tab_upload') ?></a></li>
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
            'placeholder'=>Yii::$app->_L->get('numTasks_placeholder')
            , 'value' => $model->numTasks
            , 'autofocus' => 'true'
            ,
            ])
            ->label(Yii::$app->_L->get('numTasks_label'))
            ; ?>


          <?= $form->field($model, 'thinkingMinutes'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('thinkingMinutes_placeholder')
            , 'value' => $model->thinkingMinutes
            ,
            ])
            ->label(Yii::$app->_L->get('thinkingMinutes_label'))
            ; ?>

          <?= $form->field($model, 'numTeamsize'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('numTeamsize_placeholder')
            , 'value' => $model->numTeamsize
            ,
            ])
            ->label(Yii::$app->_L->get('numTeamsize_label'))
            ; ?>

          <?= $form->field($model, 'numStudents'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('numStudents_placeholder')
            , 'value' => $model->numStudents
            ,
            ])
            ->label(Yii::$app->_L->get('numStudents_label'))
            ; ?>

            
        <!--
        <div class="btn-group input-group-lesson">
          <button type="button" class="btn btn-default" onclick='btnGroupToggle(this, "Lesson-typetasks", "text")'>
          <?php //echo Yii::$app->_L->get('typeTasks_label_short') ?></button>
          <button type="button" class="btn btn-success" onclick='btnGroupToggle(this, "Lesson-typetasks", "textarea")'>
          <?php // echo Yii::$app->_L->get('typeTasks_label_long') ?></button>
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
            <?= Html::submitButton(Yii::$app->_L->get('lesson_btn_submit'), ['class' => 'btn btn-primary', 'id'=>'lesson_btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
