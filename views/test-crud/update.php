<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LessonForm */

$this->title = 'Update Lesson Form: ' . $model->startKey;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->startKey, 'url' => ['view', 'id' => $model->startKey]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lesson-form-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
