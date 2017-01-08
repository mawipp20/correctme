<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LessonForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lesson-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'startKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numTasks')->textInput() ?>

    <?= $form->field($model, 'numStudents')->textInput() ?>

    <?= $form->field($model, 'numTeamsize')->textInput() ?>

    <?= $form->field($model, 'thinkingMinutes')->textInput() ?>

    <?= $form->field($model, 'typeTasks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'typeMixing')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
