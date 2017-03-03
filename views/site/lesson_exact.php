<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);


$this->title = Yii::$app->_L->get("lesson_title");

/* @var $this yii\web\View */
/* @var $model app\models\Lesson */
/* @var $form ActiveForm */
?>


<h3 style="margin-top: 0em; margin-bottom: 20px;"><?= Yii::$app->_L->get('LESSON_WELCOME') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

<ul class="nav nav-tabs" style="margin-bottom: 20px;">
  <li><a href="lesson"><?= Yii::$app->_L->get('lesson_nav_tab_quick') ?></a></li>
  <li class="active"><a href="#"><?= Yii::$app->_L->get('lesson_nav_tab_exact') ?></a></li>
  <li><a href="lesson_upload"><?= Yii::$app->_L->get('lesson_nav_tab_upload') ?></a></li>
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
            'placeholder'=>Yii::$app->_L->get('thinkingMinutes_placeholder')
            , 'value' => $model->thinkingMinutes
            , 'autofocus' => 'true'
            ])
            ->label(Yii::$app->_L->get('thinkingMinutes_label'))
            ; ?>

          <?= $form->field($model, 'numTeamsize'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('numTeamsize_placeholder')
            , 'value' => $model->numTeamsize
            ,
            ])
            ->label(Yii::$app->_L->get('numTeamsize_label'))
            ; ?>

          <?= $form->field($model, 'numStudents'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('numStudents_placeholder')
            , 'value' => $model->numStudents
            ,
            ])
            ->label(Yii::$app->_L->get('numStudents_label'))
            ; ?>




    <h4 style="margin-top: 1.5em; margin-bottom: 1em; "><?= Yii::$app->_L->get('lesson_tasks_title'); ?></h4>


    <input type='hidden' id='new_tasks' name='new_tasks' value=''>       
    <?= $form->field($model, 'numTasks',[])->hiddenInput(['value'=>1])->label(false); ?>
      
    
    <div id="tasks">
        
        <div class='input-group task'>
        <label class="input-group-addon input-group-addon-tasks">

            <div class="dropdown task_type" data-task-type="textarea">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"><?= Yii::$app->_L->get('lesson_tasks_type_text'); ?>
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a data-task-type="textarea" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_text').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how_often" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_how_often').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how_true" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_how_true').'&nbsp;&nbsp;'; ?></a></li>
                </ul>
            </div>
        
        
        </label>
        <textarea rows="1" data-text-length="0" class="form-control task_input" oninput="taskOnInput(this);" style="border-radius: 4px;" placeholder="<?php echo Yii::$app->_L->get('lesson_tasks_first_placeholder_explain_strg_v');?>"></textarea>
        <label class="input-group-addon task_action_buttons_label" style="padding:0em;background:transparent;border-color:transparent;">
        
        <button class="btn btn-default btn_delete_task" type="button" onclick="task_remove(this);"><i class="fa fa-chain-broken" aria-hidden="true"></i></button>
        
<!--        
        <button class="btn btn-default btn_sort_task btn_sort_task_inactive" type="button" onclick="task_start_sort(this);"><i class="fa fa-sort" aria-hidden="true"></i></button>
-->    
        </label>    
        </div>
    </div>    
            
        <?php //echo $form->field($model, 'earlyPairing') ?>
        <?php //echo $form->field($model, 'namedPairing') ?>
        <?php //echo $form->field($model, 'typeTasks') ?>
        <?php //echo $form->field($model, 'typeMixing') ?>

        <div class="form-group" style="margin-top: 2em; display: none;">
            <?php echo  Html::submitButton(Yii::$app->_L->get('lesson_btn_submit')
            , [
                'class' => 'btn btn-primary',
                'id'=>'lesson_btn_submit',
                'onsubmit' => 'lesson_exact_onsubmit();'
                ]) ?>
        </div>
       
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
