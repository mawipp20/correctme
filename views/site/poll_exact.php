<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);


$this->title = Yii::$app->_L->get("poll_title");

?>


<h3 style="margin-top: 0em; margin-bottom: 20px; line-height:150%;"><?= Yii::$app->_L->get('poll_welcome') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            $this_class = "alert ";
            if(substr($key, 0, 6) == "error_"){$this_class .= "alert-danger";}
            if(substr($key, 0, 8) == "warning_"){$this_class .= "alert-warning";}
            if(substr($key, 0, 8) == "success_"){$this_class .= "alert-success";}
            echo '<div class="'.$this_class.'">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

<ul class="nav nav-tabs" style="margin-bottom: 20px;">
  <li class="active"><a href="#"><?= Yii::$app->_L->get('poll_nav_tab_exact') ?></a></li>
  <li><a href="poll_upload"><?= Yii::$app->_L->get('poll_nav_tab_upload') ?></a></li>
</ul>

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                'action' => ['think'],
                'method' => 'post',
                'id' => 'lesson_form',
    ]); ?>

          <?= $form->field($model, 'type')->hiddenInput(['value' => 'poll'])->label(false); ?>

    <h4 style="margin-top: 1.5em; margin-bottom: 1em;"><?= Yii::$app->_L->get('poll_tasks_title'); ?></h4>


    <input type='hidden' id='new_tasks' name='new_tasks' value=''>       
    <?= $form->field($model, 'numTasks',[])->hiddenInput(['value'=>1])->label(false); ?>
      
    
    <div id="tasks">
        
        <div class='input-group task'>
        <label class="input-group-addon input-group-addon-tasks">

            <div class="dropdown task_type" data-task-type="how-true">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"><?= Yii::$app->_L->get('poll_tasks_type_how-true'); ?>
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a data-task-type="how-true" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('poll_tasks_type_how-true').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how-often" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('poll_tasks_type_how-often').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="text" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('poll_tasks_type_text').'&nbsp;&nbsp;'; ?></a></li>
                </ul>
            </div>
        
        
        </label>
        <textarea rows="1" data-text-length="0" class="form-control task_input" oninput="taskOnInput(this);" style="border-radius: 4px;" placeholder="<?php echo Yii::$app->_L->get('poll_tasks_first_placeholder_explain_strg_v');?>"></textarea>
        <label class="input-group-addon task_action_buttons_label" style="padding:0em;background:transparent;border-color:transparent;">
        
        <button class="btn btn-default btn_delete_task" type="button" onclick="task_remove(this);"><i class="fa fa-chain-broken" aria-hidden="true"></i></button>
        
        </label>    
        </div>
    </div>    

        <div id="div_lesson_submit" class="well" style="margin-top: 2em; display: none;">
        
            <?= Yii::$app->_L->get('poll_next_step') ?>

            &nbsp;&nbsp;
        
            <?php echo  Html::submitButton(
                '<i class="fa fa-user-plus" aria-hidden="true"></i>'
                .'&nbsp;&nbsp;&nbsp;&nbsp;'
                .Yii::$app->_L->get('poll_btn_submit')
            , [
                'class' => 'btn btn-primary',
                'id'=>'lesson_btn_submit',
                'onsubmit' => 'lesson_exact_onsubmit();'
                ]) ?>
        </div>
       
    <?php ActiveForm::end(); ?>

</div>
<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;
var uploadedTasks = <?= json_encode($uploadedTasks); ?>;
</script>

<?php //echo '<div class="alert alert-waring">' . print_r($uploadedTasks) . "</div>\n"; // print_r($uploadedTasks, true) ?>


<!-- Lesson -->
