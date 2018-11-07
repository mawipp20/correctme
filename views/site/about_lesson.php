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
ul.about_poll_text{
    list-style-type: none;
    padding-left: 3em;
    text-indent: -3em; 
}
span.info_more{
    font-style: italic;
    display:none;
}
.about_poll_text li:before{
   font-family: FontAwesome;
   padding-right: 10px;
   font-size: 18pt;
}
.about_poll_text li.li_a:before{
   /* <i class="fa fa-terminal" aria-hidden="true"></i> */  
   content: "\f120";
}
.about_poll_text li.li_b:before{
   /* <i class="fa fa-user-o" aria-hidden="true"></i> */ 
   content: "\f2c0";
}
.about_poll_text li.li_c:before{
   /* <i class="fa fa-users" aria-hidden="true"></i>  */ 
   content: "\f0c0";
}
.about_poll_text li.li_d:before{
   /* <i class="fa fa-address-card" aria-hidden="true"></i> */ 
   content: "\f046";
}
</style>

<script>
var _L = <?= json_encode(Yii::$app->_L->get('general')); ?>;
</script>

<div class="site-about">
    <h2 style="margin-top: 0em;"><?= Yii::$app->_L->get('lesson_welcome') ?></h2>
    <div class='well' style="padding: 0.5em; background: rgb(223,240,216);">
        <ul class="about_poll_text">
        <li class="li_a">&nbsp;&nbsp;<?= Yii::$app->_L->get('about_lesson_text_a') ?>
            <a href="" onclick="info_span_expand(this, true);return false;">...<?= Yii::$app->_L->get('gen_in_detail') ?></a>
            <span class="info_more"><br><?= Yii::$app->_L->get('about_lesson_text_more_a') ?></span>
        </li>
        
        <li class="li_b">&nbsp;&nbsp;<?= Yii::$app->_L->get('about_lesson_text_b') ?>
            <a href="" onclick="info_span_expand(this, true);return false;">...<?= Yii::$app->_L->get('gen_in_detail') ?></a>
            <span class="info_more"><br><?= Yii::$app->_L->get('about_lesson_text_more_b') ?></span>
        </li>
        <li class="li_c">&nbsp;<?= Yii::$app->_L->get('about_lesson_text_c') ?>
            <a href="" onclick="info_span_expand(this, true);return false;">...<?= Yii::$app->_L->get('gen_in_detail') ?></a>
            <span class="info_more"><br><?= Yii::$app->_L->get('about_lesson_text_more_c') ?></span>
        </li>
        <li class="li_d">&nbsp;<?= Yii::$app->_L->get('about_lesson_text_d') ?>
            <a href="" onclick="info_span_expand(this, true);return false;">...<?= Yii::$app->_L->get('gen_in_detail') ?></a>
            <span class="info_more"><br><?= Yii::$app->_L->get('about_lesson_text_more_d') ?></span>
        </li>
        </ul>
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
