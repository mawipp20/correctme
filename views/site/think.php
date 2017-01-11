<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = _L("THINK_TITLE");
$this->params['breadcrumbs'][] = $this->title;
?>

<link href="css/correctme.css" rel="stylesheet">
<script src="js/correctme_elements.js"></script>

<div class="think">

<div class="page-header">
  <h1>Example Page Header</h1>
</div>
    <div class="alert alert-success" style="color: black; font-size: 2em; text-align:center">Login: <?= $model->startKey ?></div>
    
    <?= Html::button('think_btn_submit', [
        'class' => 'btn btn-primary'
        ,'onclick' => 'getThinkingStudents();'
        ]) ?>

    <p id="ajaxDisplay"></p>

</div>
