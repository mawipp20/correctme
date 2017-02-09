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
                ,'method' => "get"    
    ]); ?>

        <?= Html::button('<i class="fa fa-arrow-right" aria-hidden="true"></i> '._L('rejoin_session_btn_new_session'), [
            'class' => 'btn btn-default input-group-lesson'
            ,'onclick' => 'window.document.location = "index";'
            ]) ?>

          <?= $form->field($model, 'startKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('startKey_placeholder')
            , 'value' => $model->startKey
            , 'autofocus' => 'true'
            ,
            ])
            ->label(_L('startKey_label'))
            ; ?>


          <?= $form->field($model, 'teacherKey'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
          )->textInput(['placeholder'=>_L('teacherKey_placeholder'), 'value' => $model->teacherKey])
          ->label(_L('teacherKey_label'))
          ; ?>



        <div class="form-group" style="margin-top: 1em;">
            <?= Html::submitButton(_L('rejoin_session_btn_submit'), ['class' => 'btn btn-primary', 'id'=>'rejoin_session_btn_submit']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(_L('_L_lesson')); ?>;</script>

<!-- Lesson -->
