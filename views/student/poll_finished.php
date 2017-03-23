<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

$this->title = 'Finished';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h3><?= Yii::$app->_L->get('poll_finished_student_title') ?></h3>


    <p>
        <?= Yii::$app->_L->get('poll_finished_student_message') ?>
    </p>

</div>
