<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\TeachersAsset;
TeachersAsset::register($this);


$this->title = Yii::$app->_L->get("teacher_title");

?>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            $this_class = "alert ";
            if(substr($key, 0, 6) == "error_"){$this_class .= "alert-danger";}
            if(substr($key, 0, 8) == "warning_"){$this_class .= "alert-warning";}
            if(substr($key, 0, 8) == "success_"){$this_class .= "alert-success";}
            echo '<div class="'.$this_class.'">' . $message . "</div>\n";
        }
    ?>

<div class="alert alert-success" style="margin-top: 0em; margin-bottom: 20px; font-size: large;">
    <?php
        $msg_success = Yii::$app->_L->get('teacher_poll_codes_title');
        $deadline = new DateTime();
        $deadline->add(new DateInterval('PT' . $lesson->thinkingMinutes . 'M'));
        $deadline_results = new DateTime();
        $deadline_results->add(new DateInterval('PT'.($lesson->thinkingMinutes + 10080).'M'));
        $msg_success = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline).'</b>', $msg_success); 
        $msg_success = str_replace('#deadline_results#', '<b>'.Yii::$app->formatter->asDate($deadline_results).'</b>', $msg_success); 
        $msg_success = str_replace('#lesson_title#', '<b>'.$lesson->title.'</b>', $msg_success); 
        echo $msg_success;      
    ?>
</div>

    <blockquote>
    <p><?= Yii::$app->_L->get('teacher_poll_codes_explanation') ?></p>
    <?php   if($lesson->poll_show_teacher_names){
                echo '<p>'.Yii::$app->_L->get('teacher_poll_codes_explanation_show_teacher_names').'</p>';   
            }
    ?>
    </blockquote>


<div id="poll-codes">
  <div class="row th">
    <div class="col-md-3 name">
        <?php echo Yii::$app->_L->get('teacher_poll_codes_participant'); ?>
        <a id="justToAlignVerticallyNoFunctionality" class='btn' style='visibility:hidden;'>&nbsp;</a>
    </div>
    <div class="col-md-9 activationkey">
        <?php echo Yii::$app->_L->get('teacher_poll_codes_activationcode'); ?>
        &nbsp;&nbsp;
        <a class='btn btn-default' style='border-color:  black;' href="download_activationcodes">
        <i class="fa fa-save" aria-hidden="true"></i>
        </a> 
    </div>
  </div>
  <?php 
    foreach($teachers as $teacher){
        $css_class_initiator = "";
        if($teacher['name'] == $initiator->name){
            $css_class_initiator = " initiator";
        }
        echo '<div class="row tr'.$css_class_initiator.'">';
        echo '<div class="col-md-3 name">'.$teacher['name'].'</div>';
        echo '<div class="col-md-9 activationkey">'.$teacher['activationkey'].'</div>';
        echo '</div>';
    }
  ?>
</div>