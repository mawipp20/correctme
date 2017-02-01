<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h3><?= _L('about_title') ?></h3>


    <p>
        <?= _L('about_text') ?>
    </p>

</div>
