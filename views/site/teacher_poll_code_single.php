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

<div class="alert alert-success" style="margin-top: 0em; margin-bottom: 20px;">
    <?php
        $msg_success = Yii::$app->_L->get('teacher_join_poll_login_success_team');
        $msg_success = str_replace('#lesson_title#', '"<b>'.$lesson->title.'</b>"', $msg_success);
         
        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
        $deadline_str = "<span style='white-space:nowrap;'>".Yii::$app->formatter->asDate($deadline)."</span>";
        
        $msg_deadline = Yii::$app->_L->get('teacher_join_poll_one_activation_key_success_deadline');
        $msg_deadline = str_replace('#deadline#', '<b>'.$deadline_str.'</b>', $msg_deadline);
         
        $deadline_results = $deadline;
        $deadline_results->modify('+2 week');
        $deadline_results_str = "<span style='white-space:nowrap;'>".Yii::$app->formatter->asDate($deadline_results)."</span>";
        
        $msg_deadline_results = Yii::$app->_L->get('teacher_join_poll_one_activation_key_success_deadline_results');
        $msg_deadline_results = str_replace('#deadline_results#', '<b>'.$deadline_results_str.'</b>', $msg_deadline_results);
         
        echo $msg_success."<br /><br />".$msg_deadline."<br />".$msg_deadline_results;      
    ?>
</div>




<h4 style="margin-bottom: 1em; margin-top: 2em;">
<?php echo Yii::$app->_L->get('teacher_poll_codes_activation_key'); ?>
</h4>

<div class="alert alert-info" style="margin-bottom: 2em; margin-top: 0.5em; padding-left: 3em; padding-top: 0.1em; padding-bottom:0.1em; color: black;">
<?php  
    echo "<span style='font-size: 32pt;'>";
    echo $template_teacher->activationkey;
    echo "</span><span style='font-size: 16pt; color: black;'>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp; ".Yii::$app->_L->get('gen_valid_until')." ".$deadline_str;
    echo "</span>";
?>
</div>

<h4 style="margin-bottom: 1em; margin-top: 2em;">
<?php echo Yii::$app->_L->get('teacher_poll_codes_result_key'); ?>
</h4>

<div class="alert alert-warning" style="margin-bottom: 2em; margin-top: 0.5em; padding-left: 3em; padding-top: 0.1em; padding-bottom:0.1em;">

<?php  
    echo "<span style='font-size: 32pt; color: black;'>";
    echo $template_teacher->resultkey;
    echo "</span><span style='font-size: 16pt; color: black;'>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp; ".Yii::$app->_L->get('gen_valid_until')." ".$deadline_results_str;
    echo "</span>";
?>
</div>

<div class="no-print">
    <button class="btn btn-primary" onclick='
        window.print();
    '>
        <i class="fa fa-print" aria-hidden="true"></i>
        Diese Seite
    </button>
    <button class="btn btn-primary" onclick='
        window.location.href = "teacher_poll_code_single_print";
    '>
        <i class="fa fa-print" aria-hidden="true"></i>
        Infos für Lehrer/innen
    </button>
</div>

