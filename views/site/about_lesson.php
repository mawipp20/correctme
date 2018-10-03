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
    <h2 style="margin-top: 0em;"><?= Yii::$app->_L->get('lesson_welcome') ?></h2>
    <div class='well' style="padding: 0.5em; background: rgb(223,240,216);">
        <?= Yii::$app->_L->get('about_lesson_text') ?>
    </div>
    <p style="line-height:  180%;">
    
    <a href="#" onclick='about_poll_goto(this, "imprint");return false;'>
    > <?= Yii::$app->_L->get('about_imprint_title') ?></a><br />
    <a href='#' onclick='about_poll_goto(this, "mission");return false;'>
    > <?= Yii::$app->_L->get('about_mission_title') ?></a><br />
    <a href="#" onclick='about_poll_goto(this, "data_protection");return false;'>
    > <?= Yii::$app->_L->get('about_data_protection_title') ?></a><br />
    <a href="#" onclick='about_poll_goto(this, "disclaimer");return false;'>
    > <?= Yii::$app->_L->get('about_disclaimer_title') ?></a>
    </p>

    

        <style>
        .cm-info-well{
        }
        .cm-info-well h4{
            margin-top: 0.2em; margin-bottom: 0.2em;
        }
        .cm-info-well p{
            margin-left: 1em;
            font-size: larger;
        }
        .activationkey{
            font-weight: bold;
            color: darkorange;
            white-space: nowrap;
        }
        .studentkey{
            font-weight: bold;
            color: green;
            white-space: nowrap;
        }
        .resultkey{
        }
        h4{
            padding: 0.3em;
            padding-bottom: 0em;
            border-top:4px solid rgb(217,237,247);
        }
        p.headline2{
            font-size: 18px;
            margin-bottom: 1em;
        }
        p.sequence_info_link{
            margin-top:1.5em;
            cursor: pointer; 
            color: darkblue;
            font-size: larger;
        }
        ul {
            list-style-type: "... ";
        }
        </style>
       
               
    <h3 id='imprint'><?= Yii::$app->_L->get('about_imprint_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_imprint_text') ?>
    </p>
    <h3 id='mission'><?= Yii::$app->_L->get('about_mission_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_mission_text') ?>
    </p>
    <h3 id='privacy_total'><?= Yii::$app->_L->get('about_data_protection_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_data_protection_text') ?>
    </p>
    <h3 id='disclaimer'><?= Yii::$app->_L->get('about_disclaimer_title') ?></h3>
    <p>
        <?= Yii::$app->_L->get('about_disclaimer_text') ?>
    </p>





</div>
