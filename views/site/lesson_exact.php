<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

$this->title = _L("lesson_title");

/* @var $this yii\web\View */
/* @var $model app\models\Lesson */
/* @var $form ActiveForm */
?>


<h3 style="margin-top: 0em; margin-bottom: 20px;"><?= _L('LESSON_WELCOME') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

<ul class="nav nav-tabs" style="margin-bottom: 20px;">
  <li><a href="lesson"><?= _L('lesson_nav_tab_quick') ?></a></li>
  <li class="active"><a href="#"><?= _L('lesson_nav_tab_exact') ?></a></li>
  <li><a href="lesson_paste"><?= _L('lesson_nav_tab_paste') ?></a></li>
  <li><a href="lesson_upload"><?= _L('lesson_nav_tab_upload') ?></a></li>
</ul>

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                'action' => ['think'],
                'method' => 'post',
                'id' => 'lesson_form',
    ]); ?>


          <?= $form->field($model, 'thinkingMinutes'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('thinkingMinutes_placeholder')
            , 'value' => $model->thinkingMinutes
            ,
            ])
            ->label(_L('thinkingMinutes_label'))
            ; ?>

          <?= $form->field($model, 'numTeamsize'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('numTeamsize_placeholder')
            , 'value' => $model->numTeamsize
            ,
            ])
            ->label(_L('numTeamsize_label'))
            ; ?>

          <?= $form->field($model, 'numStudents'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>_L('numStudents_placeholder')
            , 'value' => $model->numStudents
            ,
            ])
            ->label(_L('numStudents_label'))
            ; ?>


            <?=  $form->field($model, 'numTasks')->hiddenInput(['value'=> 1])->label(false); ?>       


    <h4 style="margin-top: 1.5em; margin-bottom: 1em; "><?= _L('lesson_tasks_title'); ?></h4>


    <div id="tasks">
        
        <div class='input-group task'>
        <label class="input-group-addon input-group-addon-tasks">

            <div class="dropdown task_type" data-task-type="text">
                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><?= _L('lesson_tasks_type_text'); ?>
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a data-task-type="textarea" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo _L('lesson_tasks_type_text').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how_often" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo _L('lesson_tasks_type_how_often').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how_true" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo _L('lesson_tasks_type_how_true').'&nbsp;&nbsp;'; ?></a></li>
                </ul>
            </div>
        
        
        </label>
        <input type="text" class="form-control task_input"
            oninput='taskOnInput(this);'
            style="border-bottom-left-radius: 4px;border-top-left-radius: 4px;"
            placeholder="<?php echo _L('lesson_tasks_input_placeholder');?>" />
        </div>
            
    </div>    
            
        <?php //echo $form->field($model, 'earlyPairing') ?>
        <?php //echo $form->field($model, 'namedPairing') ?>
        <?php //echo $form->field($model, 'typeTasks') ?>
        <?php //echo $form->field($model, 'typeMixing') ?>

        <div class="form-group" style="margin-top: 2em; display: none;">
            <?php echo  Html::submitButton(_L('lesson_btn_submit')
            , [
                'class' => 'btn btn-primary',
                'id'=>'lesson_btn_submit',
                'onsubmit' => 'lesson_exact_onsubmit();'
                ]) ?>
        </div>
       
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(_L('_L_lesson')); ?>;</script>

<!-- Lesson -->
