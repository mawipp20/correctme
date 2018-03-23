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
    <p style="line-height:  180%;">
    
    <a href="#" onclick='about_goto(this, "info_team");return false;'>
    > <?= Yii::$app->_L->get('teachers_info_team_title') ?></a><br />
    <a href="#" onclick='about_goto(this, "info_single");return false;'>
    > <?= Yii::$app->_L->get('teachers_info_single_title') ?></a><br />
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
       
       
    <h3 id='info_team'><?= Yii::$app->_L->get('teachers_info_team_title') ?></h3>
       
        
        <div id="sequence-info" style="padding: 0.3em; padding-left:2em; border: 0px solid darkgrey;">
           
            <div class='w1ell w1ell-lg cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_team_top_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_team_top_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_team_top_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_team_top_li_3') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_teacher_top_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_top_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_top_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_top_li_3') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_students_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_2') ?></p>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_teacher_bottom_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_bottom_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_bottom_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_bottom_li_3') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_team_bottom_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_team_bottom_li_1') ?></li>
                    <li  style="background-color: rgb(217,237,247);">
                    <?= Yii::$app->_L->get('info_sequence_team_bottom_li_2') ?>
                    </li>
                    <li  style="background-color: rgb(217,237,247);">
                    <?= Yii::$app->_L->get('info_sequence_team_bottom_li_3') ?>
                    </li>
                </ul>
            </div>
        </div>    
                

    <h3 id='info_single'><?= Yii::$app->_L->get('teachers_info_single_title') ?></h3>

        <div id="sequence-single-info" style="padding: 0.3em; margin-left:2em; border: 0px solid darkgrey;">
           
            <div class='w1ell w1ell-lg cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_single_team_top_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_3') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_4') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_students_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_2') ?></p>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_single_team_bottom_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_bottom_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_bottom_li_2') ?></li>
                </ul>
            </div>
        </div>    


                
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
