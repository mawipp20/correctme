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

    /** title */

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
    ; 

    /** description */
    
    $description_css_display = 'none';
    if($model->description != ""){$description_css_display = 'inline';}
    echo $form->field($model, 'description'
    , [
    'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson'
                        , 'style' => 'min-width: 80px; padding-right: 0.5em;' ]
    ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
    ,'options' => ['style' => 'display:'.$description_css_display.';']
    ]
    )->textarea([
    'placeholder'=>Yii::$app->_L->get('lesson_description_placeholder')
    , 'value' => $model->description
    , 'autofocus' => true
    , 'style' => ''
    ])
    ->label(Yii::$app->_L->get('lesson_description_label'))
    ; 
    ?>

    <input type='hidden' id='new_tasks' name='new_tasks' value=''>       
    <?= $form->field($model, 'numTasks',[])->hiddenInput(['value'=>1])->label(false); ?>



    <p>
                <?php
                if($model->description==""){
                    echo  Html::a(Yii::$app->_L->get('poll_descripton_show_textarea_link')
                    , ['#']
                    , [ 'onclick' => '$(".field-lesson-description").show();
                                      $("#lesson-description").focus();
                                      $("#poll_descripton_show_textarea").hide();
                                      return false;'
                        ,'id' => 'poll_descripton_show_textarea'
                      ]
                    );
                }
                ?>
                
    </p><p>
                <?php         
                $caption_text = "";
                //$caption_text = '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;&nbsp;';
                $caption_text .= Yii::$app->_L->get('poll_tasks_text_edit_mode_link');
                
                $caption_input = '<i class="fa fa-bars" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;';
                $caption_input .= Yii::$app->_L->get('poll_tasks_text_input_fields_mode_link');
                
                echo  Html::a($caption_text
                , ['#']
                , [ 'onclick' => 'toggle_text_mode();return false;'
                    ,'id' => 'poll_tasks_input_mode_link'
                  ]
                ) ?>
                <script>var poll_tasks_input_mode = <?php echo json_encode(array("text"=>$caption_text, "input"=>$caption_input)); ?>;
                </script>
    </p>

    <div id="tasks_edit" style="display:none;">
        <p>
        <textarea id="tasks_edit_textarea" rows="1" data-text-length="0" class="form-control"
        style="border-radius: 4px;"></textarea>
        </p>
    </div>
      
    
    <div id="tasks">
        
        <div class='input-group task'>
        <label class="input-group-addon input-group-addon-tasks">

            <div class="dropdown task_type" data-task-type="how-true">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"><?= Yii::$app->_L->get('poll_tasks_type_how-true'); ?>
                <span class="caret"></span></button>
                <ul class="dropdown-menu">

                <?php
                    foreach($model->taskTypesOrder["poll"] as $this_task_type){
                        echo '<li><a data-task-type="'.$this_task_type.'" href="#"';
                        echo 'onclick="dropdown_task_type(this); return false;">';
                        echo Yii::$app->_L->get('poll_tasks_type_'.$this_task_type).'&nbsp;&nbsp;';
                        echo "</a></li>";
                    }
                
                ?>
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
                            if(lesson_exact_validate_tasks()===false){return false;}'
                ]) ?>

            <?php echo  Html::submitButton(Yii::$app->_L->get('poll_submit_team')
                , [
                'class' => 'btn btn-primary',
                'id'=>'poll_exact_submit',
                'onclick' => '$("#poll_type").val("team");
                                if(lesson_exact_validate_tasks()===false){return false;}'
                ]) ?>

        </div>
       
    <?php ActiveForm::end(); ?>

</div>

<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;
var _L_poll = <?= json_encode(Yii::$app->_L->get('poll')); ?>;
var _L_student_think = <?= json_encode(Yii::$app->_L->get('student_think')); ?>;
var uploadedTasks = <?= json_encode($uploadedTasks); ?>;
var controller_lesson = <?= json_encode($model); ?>;
</script>

<?php //echo '<div class="alert alert-waring">' . print_r($uploadedTasks) . "</div>\n"; // print_r($uploadedTasks, true) ?>


<!-- Lesson -->
