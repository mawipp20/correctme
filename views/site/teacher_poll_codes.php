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
        $msg_success = str_replace('#lesson_title#', '<b>'.$lesson->title.'</b>', $msg_success); 


        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
        
        //$msg_deadline = Yii::$app->_L->get('teacher_join_poll_one_activation_key_success_deadline');
        //$msg_deadline = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline).'</b>', $msg_deadline);
        $msg_success = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline).'</b>', $msg_success); 
         
        $deadline_results = $deadline;
        $deadline_results->modify('+2 week');
        
        $msg_success = str_replace('#deadline_results#', '<b>'.Yii::$app->formatter->asDate($deadline_results).'</b>', $msg_success); 
        echo $msg_success;      
    ?>
</div>

    <blockquote>
    <p><?= Yii::$app->_L->get('teacher_poll_codes_explanation') ?></p>
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
    $list_t = "";
    $template_t = "";
    foreach($teachers as $teacher){
        $css_class_template_teacher = "";
        $this_name = $teacher['name'];
        if($this_name == "template_do_not_display"){
            $css_class_template_teacher = " template_teacher";
            $this_name = Yii::$app->_L->get('teacher_poll_codes_template_activationkey');
        }
        $t =  '<div class="row tr'.$css_class_template_teacher.'">';
        $t .= '<div class="col-md-3 name">'.$this_name.'</div>';
        $t .= '<div class="col-md-9 activationkey">'.$teacher['activationkey'].'</div>';
        $t .= '</div>';
        if($teacher['name'] == "template_do_not_display"){
            $template_t = $t;
        }else{
            $list_t .= $t;
        }
    }
    echo $template_t.$list_t;
    
  ?>
</div>