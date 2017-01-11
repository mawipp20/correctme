<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

        <div class="input-group input-group-lesson">
          <div class="input-group-addon input-group-addon-lesson">
            <?= _L('numTasks_label') ?>
          </div>
          <?= $form->field($model, 'numTasks')->textInput(['placeholder'=>_L('numTasks_placeholder'), 'value' => $model->numTasks]); ?>
        </div>

        <div class="input-group input-group-lesson">
          <div class="input-group-addon input-group-addon-lesson">
            <?= _L('thinkingMinutes_label') ?>
          </div>
          <?= $form->field($model, 'thinkingMinutes')->textInput(['placeholder'=>_L('thinkingMinutes_placeholder')]); ?>
        </div>

        <div class="input-group input-group-lesson">
          <div class="input-group-addon input-group-addon-lesson">
            <?= _L('numTeamsize_label') ?>
          </div>
          <?= $form->field($model, 'numTeamsize')->textInput(['placeholder'=>_L('numTeamsize_placeholder')]); ?>
        </div>

        <div class="input-group input-group-lesson">
          <div class="input-group-addon input-group-addon-lesson">
            <?= _L('numStudents_label') ?>
          </div>
          <?= $form->field($model, 'numStudents')->textInput(['placeholder'=>_L('numStudents_placeholder')]); ?>
        </div>

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
            <?= Html::submitButton(_L('lesson_btn_submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- Lesson -->
