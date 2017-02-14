<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\StudentAsset;
StudentAsset::register($this);


$this->title = _L("THINK_TITLE");
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="think">

    <?= Html::hiddenInput('startKey', Yii::$app->getSession()->get("startKey"))  ?>
    <?= Html::hiddenInput('studentKey', Yii::$app->getSession()->get("studentKey"))  ?>

<!-- <div class="page-header">

<h1>
 <div style="float:left;">
    <?php //echo $model->name ?>
    </div>

  
    <div style="float:right;">
    
    </div>
       
    <div style="clear: right;"></div>

</h1>
-->

    <div id="displayTasks"></div>


</div>

