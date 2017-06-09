<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\TeachersAsset;
TeachersAsset::register($this);

$this->title = Yii::$app->_L->get("teacher_title");

        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            $this_class = "alert ";
            if(substr($key, 0, 6) == "error_"){$this_class .= "alert-danger";}
            if(substr($key, 0, 8) == "warning_"){$this_class .= "alert-warning";}
            if(substr($key, 0, 8) == "success_"){$this_class .= "alert-success";}
            echo '<div class="'.$this_class.'">' . $message . "</div>\n";
        }

        $msg_success = Yii::$app->_L->get('teacher_join_poll_login_success_single');
        $msg_success = str_replace('#lesson_title#', '"<b>'.$lesson->title.'</b>"', $msg_success);
         
        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
        
        //$msg_deadline = Yii::$app->_L->get('teacher_join_poll_login_success_deadline');
        $msg_deadline = Yii::$app->_L->get('teacher_join_poll_studentkey');
        $msg_deadline = str_replace('#deadline#', Yii::$app->formatter->asDate($deadline), $msg_deadline);
         
        $deadline_results = $deadline;
        $deadline_results->modify('+2 week');
        
        //$msg_deadline_results = Yii::$app->_L->get('teacher_join_poll_login_success_deadline_results');
        $msg_deadline_results = Yii::$app->_L->get('teacher_join_poll_resultkey');
        $msg_deadline_results = str_replace('#deadline_results#', Yii::$app->formatter->asDate($deadline_results), $msg_deadline_results);

?>

<div class="alert alert-success" style="margin-top: 0em; margin-bottom: 20px;">
    <?php
        echo $msg_success;      
    ?>
</div>

<?php if ($teacher->name == $teacher->activationkey): ?>

    <div id="div_save_name" class="alert alert-success" style="margin-top: 0em; margin-bottom: 20px;">
    
     <p style="margin-bottom: 0.5em;">
        <?= Yii::$app->_L->get('teacher_join_poll_add_my_name_info'); ?>
    </p>
   
   <input type='hidden' id='activationkey' name='activationkey' value='<?= $teacher->activationkey ?>'>       

    <p style="margin-bottom: 0.5em;">
        <div class="input-group input-group-lesson">
        <label class="input-group-addon" style="min-width: 80px; padding-right: 0.5em;" for="teacher-name">
        <?= Yii::$app->_L->get('teacher_join_poll_add_my_name_label'); ?>
        </label>
        <input id="teacher-name" class="form-control" name="teacher" placeholder="
        <?= Yii::$app->_L->get('teacher_join_poll_add_my_name_placeholder'); ?>
        " autofocus="true" type="text">
        </div>
    </p>
    <p>
        <div id="div_save_name_error" class="alert alert-danger" style="display:none;"></div>
        <?= Html::button(Yii::$app->_L->get('teacher_join_poll_add_my_name_save'), [
            'class' => 'btn btn-primary'
            ,'onclick' => 'teacher_join_poll_save_name();'
            ]) ?>
        </div>
    </p>

    
    <div id="div_save_name_success" class="alert alert-success" style="display:none;"></div>
    

<?php endif; ?>

<p style="font-size: larger; margin-bottom: 0em;">
<?php echo $msg_deadline; ?>
</p>
<div class="alert alert-info" style="margin-bottom: 2em; margin-top: 0.5em; padding-left: 3em; padding-top: 0.1em; padding-bottom:0.1em;">
<?php 
    echo "<span style='font-size: 32pt; color: black;'>".$teacher->studentkey."</span>";
?>
</div>

<p style="font-size: larger; margin-bottom: 0em;">
<?php echo $msg_deadline_results; ?>
</p>
<div class="alert alert-warning" style="margin-bottom: 1.5em;margin-top: 0.5em; padding-left: 3em; padding-top: 0.1em; padding-bottom:0.1em;">
<?php 
    echo "<span style='font-size: 32pt; color: black;'>".$teacher->resultkey."</span>";
?>
</div>

<!--
<div class="alert alert-success" style="margin-top: 0em; margin-bottom: 20px;">
    <?php
        //echo $msg_deadline."<br /><br />".$msg_deadline_results;      
    ?>
</div>
-->

<p style="font-size: 36pt;color:darkblue;margin:0em;padding:0em;" onmouseover="$('#screenshot_prompt').show();"><i class="fa fa-camera" aria-hidden="true"></i>
<span id="screenshot_prompt" style="display: none; font-size: 12pt; padding: 0.5em; color: white; background: darkblue;"><?= Yii::$app->_L->get('teacher_join_poll_screenshot_prompt'); ?></span>
</p>




<?php
        echo '<blockquote style="font-size: medium;">';
        echo Yii::$app->_L->get('teacher_join_poll_explanation');
        echo '</blockquote>';
?>
<script>
var _L = <?= json_encode(Yii::$app->_L->get('teacher_join_poll')); ?>;
function cmConfigO(){
    this.restcorrectmeBaseUrl = '<?= Yii::$app->params["restcorrectmeBaseUrl"] ?>';
    this.restcorrectmePath = '<?= Yii::$app->params["restcorrectmePath"] ?>';
}
var cmConfig = new cmConfigO();
</script>

