<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\testCrudSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lesson-form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'startKey') ?>

    <?= $form->field($model, 'teacherId') ?>

    <?= $form->field($model, 'numTasks') ?>

    <?= $form->field($model, 'numStudents') ?>

    <?= $form->field($model, 'numTeamsize') ?>

    <?php // echo $form->field($model, 'thinkingMinutes') ?>

    <?php // echo $form->field($model, 'typeTasks') ?>

    <?php // echo $form->field($model, 'earlyPairing') ?>

    <?php // echo $form->field($model, 'typeMixing') ?>

    <?php // echo $form->field($model, 'namedPairing') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
