<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Lesson */
/* @var $form ActiveForm */
?>

<!--
<div class="form-group">
    <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
    <div class="input-group">
      <div class="input-group-addon">$</div>
      <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
      <div class="input-group-addon">.00</div>
    </div>
  </div>
-->

<h3 style="margin-top: 0.0em;"><?= _L('LESSON_WELCOME') ?></h3>
<h4 style="margin-top: 0.2em;margin-bottom: 1em;"><?= _L('LESSON_WELCOME_line_2') ?></h4>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

    <?php $form = ActiveForm::begin([
                'action' => ['think']
                ,'method' => "post"    
    ]); ?>

        <?= Html::button('<i class="fa fa-arrow-right" aria-hidden="true"></i> <span id="lesson_btn_rejoin_session">'._L('lesson_btn_rejoin_session').'</span>', [
            'class' => 'btn btn-default input-group-lesson'
            ,'onclick' => 'window.document.location = "'.\Yii::$app->homeUrl.'site/session_rejoin";'
            ]) ?>


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

        <!-- Rounded switch 
        <label class="switch">
          <?php //echo $form->field($model, 'typeTasks')->checkbox(); ?>
          <input id="lessonform-typetasks" name="LessonForm[typeTasks]" value="1" type="checkbox">
          <div class="slider round"></div>
        </label>
        -->
        
        <div class="btn-group input-group-lesson">
          <button type="button" class="btn btn-default" onclick='btnGroupToggle(this, "lessonform-typetasks", "text")'>
          <?= _L('typeTasks_label_short') ?></button>
          <button type="button" class="btn btn-success" onclick='btnGroupToggle(this, "lessonform-typetasks", "textarea")'>
          <?= _L('typeTasks_label_long') ?></button>
          <input id="lessonform-typetasks" name="LessonForm[typeTasks]" value="textarea" type="hidden">
        </div>        
        

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
