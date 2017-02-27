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

<h3 style="margin-top: 0.0em;"><?= _L('student_join_title') ?></h3>
<!-- </a><h4 style="margin-top: 0.2em;margin-bottom: 1em;"><?php //echo _L('LESSON_WELCOME_line_2'); ?></h4> -->

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

          <?= $form->field($model, 'startKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-student-join' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('student_join_startKey_placeholder')
            , 'value' => $model->startKey
            , 'autofocus' => 'true'
            ,
            ])
            ->label(_L('student_join_startKey_label'))
            ; ?>

          <?= $form->field($model, 'name'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-student-join' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('student_join_name_placeholder')
            , 'value' => $model->name
            , 'autofocus' => 'true'
            ,
            ])
            ->label(_L('student_join_name_label'))
            ; ?>


        <div class="form-group" style="margin-top: 1em;">
            <?= Html::submitButton(_L('student_join_btn_submit'), ['class' => 'btn btn-primary', 'id'=>'student_join_btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(_L('_L_lesson')); ?>;</script>

<!-- Lesson -->
