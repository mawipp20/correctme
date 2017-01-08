<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\testCrudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lesson Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-form-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Lesson Form', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'startKey',
            'teacherId',
            'numTasks',
            'numStudents',
            'numTeamsize',
            // 'thinkingMinutes',
            // 'typeTasks',
            // 'earlyPairing',
            // 'typeMixing',
            // 'namedPairing',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
