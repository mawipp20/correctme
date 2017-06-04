<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\AboutAsset;
AboutAsset::register($this);


$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
a{
    text-decoration: none;
}
</style>

<div class="site-about">
    <h2 style="margin-top: 0em;"><?= Yii::$app->_L->get('about_title') ?></h2>
    <div class='well' style="padding: 0.5em; background: rgb(223,240,216);">
        <?= Yii::$app->_L->get('about_text') ?>
    </div>
    <a name='imprint'></a>
    <p style="line-height:  180%;">
    <a href="#" onclick='about_goto(this, "imprint");return false;'>
    > <?= Yii::$app->_L->get('about_imprint_title') ?></a><br />
    <a href='#' onclick='about_goto(this, "mission");return false;'>
    > <?= Yii::$app->_L->get('about_mission_title') ?></a><br />
    <a href="#" onclick='about_goto(this, "privacy");return false;'>
    > <?= Yii::$app->_L->get('about_privacy_title') ?></a><br />
    <a href="#" onclick='about_goto(this, "privacy_total");return false;'>
    > <?= Yii::$app->_L->get('about_privacy_total_title') ?></a><br />
    <a href="#" onclick='about_goto(this, "disclaimer");return false;'>
    > <?= Yii::$app->_L->get('about_disclaimer_title') ?></a>
    </p>
    
    <h3 id='imprint'><?= Yii::$app->_L->get('about_imprint_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_imprint_text') ?>
    </p>
    <h3 id='mission'><?= Yii::$app->_L->get('about_mission_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_mission_text') ?>
    </p>
    <h3 id='privacy'><?= Yii::$app->_L->get('about_privacy_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_privacy_text') ?>
    </p>
    <h3 id='privacy_total'><?= Yii::$app->_L->get('about_privacy_total_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_privacy_total_text') ?>
    </p>
    <h3 id='disclaimer'><?= Yii::$app->_L->get('about_disclaimer_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_disclaimer_text') ?>
    </p>

</div>
