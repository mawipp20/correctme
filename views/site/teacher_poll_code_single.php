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
        
        $msg_deadline = Yii::$app->_L->get('teacher_join_poll_login_success_deadline');
        $msg_deadline = str_replace('#deadline#', '<b>'.Yii::$app->formatter->asDate($deadline).'</b>', $msg_deadline);
         
        $deadline_results = $deadline;
        $deadline_results->modify('+1 week');
        
        $msg_deadline_results = Yii::$app->_L->get('teacher_join_poll_login_success_deadline_results');
        $msg_deadline_results = str_replace('#deadline_results#', '<b>'.Yii::$app->formatter->asDate($deadline_results).'</b>', $msg_deadline_results);
         
        echo $msg_success."<br /><br />".$msg_deadline."<br />".$msg_deadline_results;      
    ?>
</div>

<h4 style="margin-bottom: 1em; margin-top: 2em;">
<?php echo Yii::$app->_L->get('teacher_poll_codes_common_key'); ?>
</h4>

<h2 style="margin-bottom: 1em; margin-top: 1em; font-size: 36pt; padding-left: 4em;">
<?php echo $template_teacher->activationkey; ?>
</h2>

    <blockquote>
    <p><?= Yii::$app->_L->get('teacher_poll_codes_explanation') ?></p>
    </blockquote>

