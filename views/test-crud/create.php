<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LessonForm */

$this->title = 'Create Lesson Form';
$this->params['breadcrumbs'][] = ['label' => 'Lesson Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lesson-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
