<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LessonForm */

$this->title = $model->startKey;
$this->params['breadcrumbs'][] = ['label' => 'Lesson Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-form-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->startKey], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->startKey], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'startKey',
            'teacherId',
            'numTasks',
            'numStudents',
            'numTeamsize',
            'thinkingMinutes',
            'typeTasks',
            'earlyPairing',
            'typeMixing',
            'namedPairing',
        ],
    ]) ?>

</div>
