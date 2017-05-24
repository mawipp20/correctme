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
        $msg_success = Yii::$app->_L->get('teacher_join_poll_login_success_single');
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

<h4 style="margin-bottom: 1.5em; margin-top: 1.5em;">
<?php 
    echo Yii::$app->_L->get('teacher_join_poll_studentkey').":&nbsp;&nbsp;&nbsp;";
    echo "<span style='font-size: 36pt;'>".$teacher->studentkey."</span>";
?>
</h4>
<h4 style="margin-bottom: 1.5em;">
<?php 
    echo Yii::$app->_L->get('teacher_join_poll_resultkey').":&nbsp;&nbsp;&nbsp;";
    echo "<span style='font-size: 36pt;'>".$teacher->resultkey."</span>";
?>
</h4>


<p style="font-weight:  bold;">
    <?= Yii::$app->_L->get('teacher_join_poll_add_my_name_info'); ?>
</p>


<?php if ($teacher->name == $teacher->activationkey): ?>

    <div id="div_save_name">
    
    <input type='hidden' id='activationkey' name='activationkey' value='<?= $teacher->activationkey ?>'>       

    <p>
    <div class="input-group input-group-lesson">
    <label class="input-group-addon" style="min-width: 80px; padding-right: 0.5em;" for="teacher-name">
    <?= Yii::$app->_L->get('teacher_join_poll_add_my_name_label'); ?>
    </label>
    <input id="teacher-name" class="form-control" name="teacher" placeholder="
    <?= Yii::$app->_L->get('teacher_join_poll_add_my_name_placeholder'); ?>
    " autofocus="true" type="text">
    </div>
    </p>
    <div id="div_save_name_error" class="alert alert-danger" style="display:none;"></div>
    <?= Html::button(Yii::$app->_L->get('teacher_join_poll_add_my_name_save'), [
        'class' => 'btn btn-primary'
        ,'onclick' => 'teacher_join_poll_save_name();'
        ]) ?>
    </div>

    
    <div id="div_save_name_success" class="alert alert-success" style="display:none;"></div>
    

<?php endif; ?>



<?php
        echo '<blockquote style="font-size: medium;">';
        echo Yii::$app->_L->get('teacher_join_poll_explanation');
        echo '</blockquote>';
?>
<script>
var _L = <?= json_encode(Yii::$app->_L->get('teacher_join_poll')); ?>;
function cmConfigO(){
    this.restcorrectmeBaseUrl = '<?= Yii::$app->params["restcorrectmeBaseUrl"] ?>';
}
var cmConfig = new cmConfigO();
</script>

