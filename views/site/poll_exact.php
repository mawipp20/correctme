<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);


$this->title = Yii::$app->_L->get("poll_title");

        
?>

<div id='switch_activate_create' class='well well-lg  well-correctme-as-button'
    style="margin-top: 1em; margin-bottom: 3em;<?php if(!$show_teacher_join){echo 'display:none;';} ?>"
    onclick='   
        $("#switch_activate_create").hide();
        $("#activate_poll").hide();
        $("#lesson_div").show();
        $("#lesson-title").focus();
        '
>
    <?= Yii::$app->_L->get('lesson_switch_activate_create') ?>
</div>



<div id='activate_poll' style="<?php if(!$show_teacher_join){echo 'display:none;';} ?>">


    <h3 style="margin-bottom: 20px;"><?= Yii::$app->_L->get('lesson_teacher_join_poll_title') ?></h3>

        <!-- activate poll -->
        
        <?php $form1 = ActiveForm::begin([
                    'action' => ['teacher_join_poll'],
                    'method' => "get",    
                    'validateOnChange'=>true,
                    'validateOnBlur'=>false,
        ]);        
        ?>

          <?= $form1->field($model, 'type')->hiddenInput(['value' => 'poll'])->label(false); ?>

        <?php if(Yii::$app->session->hasFlash('login_error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo Yii::$app->session->getFlash('login_error'); ?>
            </div>
        <?php endif; ?>

        <div class="row">

            <div  class="col-md-4">
                <?php
                echo $form1->field($teacher, 'activationkey'
                , [
                'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-teacher' ]
                ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
                ]
                )->textInput([
                'autofocus' => true,
                ])
                ->label('<b>'.Yii::$app->_L->get('lesson_teacher_join_activationkey_label').'</b>')
                ;
                ?>
            </div>

            <div class="col-md-8">
                <?= Html::submitButton(Yii::$app->_L->get('lesson_teacher_join_poll_btn_submit_activationkey'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        
        <!-- get results -->

        <?php $form2 = ActiveForm::begin([
                    'action' => ['teacher_results'],
                    'method' => "get",    
                    'validateOnChange'=>true,
                    'validateOnBlur'=>false,
        ]);
        ?>
        <?php if(Yii::$app->session->hasFlash('results_error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= Yii::$app->session->getFlash('results_error') ?>
            </div>
        <?php endif; ?>
        <div class="row" style="margin-top: 1em;">

            <div  class="col-md-4">
                <?php
                echo $form2->field($teacher, 'resultkey'
                , [
                'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-teacher' ]
                ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
                ]
                )->textInput([
                ])
                ->label('<b>'.Yii::$app->_L->get('lesson_teacher_join_resultkey_label').'</b>')
                ;
                ?>
            </div>

            <div class="col-md-8">
                <?= Html::submitButton(Yii::$app->_L->get('lesson_teacher_join_poll_btn_submit_resultkey'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

</div>


<div class="Lesson" id="lesson_div" style="<?php if($show_teacher_join){echo 'display:none;';} ?>">


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


    <ul class="nav nav-tabs" style="margin-bottom: 20px;">
      <li class="active"><a href="#"><?= Yii::$app->_L->get('poll_nav_tab_exact') ?></a></li>
      <li><a href="poll_upload"><?= Yii::$app->_L->get('poll_nav_tab_upload') ?></a></li>
    </ul>

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>true,
                'action' => ['think'],
                'method' => 'post',
                'id' => 'lesson_form',
    ]); ?>

    <?= $form->field($model, 'type')->hiddenInput(['value' => 'poll'])->label(false); ?>
    <input type='hidden' id='poll_type' name='poll_type' value='single'>       
    
    <?php
    echo $form->field($model, 'title'
    , [
    'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson'
                        , 'style' => 'min-width: 80px; padding-right: 0.5em;' ]
    ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
    ]
    )->textInput([
    'placeholder'=>Yii::$app->_L->get('lesson_title_placeholder')
    , 'value' => $model->title
    , 'autofocus' => true
    ])
    ->label(Yii::$app->_L->get('lesson_title_label'))
    ; ?>

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
                    <li><a data-task-type="info" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('poll_tasks_type_info').'&nbsp;&nbsp;'; ?></a></li>
                </ul>
            </div>
        
        
        </label>
        <textarea rows="1" data-text-length="0" class="form-control task_input" oninput="taskOnInput(this);" style="border-radius: 4px;" placeholder="<?php echo Yii::$app->_L->get('poll_tasks_first_placeholder_explain_strg_v');?>"></textarea>
        <label class="input-group-addon task_action_buttons_label" style="padding:0em;background:transparent;border-color:transparent;">
        
        <button class="btn btn-default btn_delete_task" type="button" onclick="task_remove(this);"><i class="fa fa-chain-broken" aria-hidden="true"></i></button>
        
        </label>    
        </div>
    </div>    

        <div id="div_lesson_submit" class="" style="margin-top: 2em; display: none;">
                
            <?php echo  Html::submitButton(Yii::$app->_L->get('poll_submit_single')
                , [
                'class' => 'btn btn-primary',
                'id'=>'poll_exact_submit',
                'onclick' => '$("#poll_type").val("single");
                            if(!lesson_exact_validate_tasks()){return false;}'
                ]) ?>

            <?php echo  Html::submitButton(Yii::$app->_L->get('poll_submit_team')
                , [
                'class' => 'btn btn-primary',
                'id'=>'poll_exact_submit',
                'onclick' => '$("#poll_type").val("team");
                                if(lesson_exact_validate_tasks()){this.form.submit();}'
                ]) ?>

        </div>
       
    <?php ActiveForm::end(); ?>

</div>

<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;
var _L_poll = <?= json_encode(Yii::$app->_L->get('poll')); ?>;
var uploadedTasks = <?= json_encode($uploadedTasks); ?>;
</script>

<?php //echo '<div class="alert alert-waring">' . print_r($uploadedTasks) . "</div>\n"; // print_r($uploadedTasks, true) ?>


<!-- Lesson -->
