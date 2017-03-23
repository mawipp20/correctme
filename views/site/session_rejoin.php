<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

$this->title = Yii::$app->_L->get("session_rejoin_title");

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

<h3 style="padding-top: 0em; margin-top: 0em;"><?= Yii::$app->_L->get('lesson_welcome') ?></h3>
<!-- </a><h4 style="margin-top: 0.2em;margin-bottom: 1em;"><?php //echo Yii::$app->_L->get('lesson_welcome_line_2'); ?></h4> -->

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
       
    ?>

<div class="Lesson">

    <?php $form = ActiveForm::begin([
                'action' => ['think'],
                'method' => 'get',
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                //,'options' => ['onsubmit' => 'teacherKey_to_ASCII_codes();']    
    ]); ?>

          <?= $form->field($model, 'startKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('startKey_placeholder')
            , 'value' => $model->startKey
            , 'autofocus' => 'true'
            ,
            ])
            ->label(Yii::$app->_L->get('startKey_label'))
            ; ?>


          <?= $form->field($model, 'teacherKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
          )->passwordInput(['placeholder'=>Yii::$app->_L->get('teacherKey_placeholder'), 'value' => $model->teacherKey])
          ->label(Yii::$app->_L->get('teacherKey_label'))
          ; ?>



        <div class="form-group" style="margin-top: 1em;">
            <?= Html::submitButton(Yii::$app->_L->get('rejoin_session_btn_submit'), ['class' => 'btn btn-primary', 'id'=>'rejoin_session_btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
