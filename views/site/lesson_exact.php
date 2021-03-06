<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

use yii\bootstrap\Modal;


$this->title = Yii::$app->_L->get("lesson_title");

?>


<h3 style="margin-top: 0em; margin-bottom: 20px;"><?= Yii::$app->_L->get('lesson_welcome') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            $this_class = "alert ";
            if(strpos($key, "error")!==false){$this_class .= "alert-danger";}
            if(strpos($key, "warning")!==false){$this_class .= "alert-warning";}
            if(strpos($key, "success")!==false){$this_class .= "alert-success";}
            echo '<div class="'.$this_class.'">' . $message . "</div>\n";
        }
    ?>

<div class="Lesson">

<?php //echo '<div class="alert alert-waring">' . print_r($uploadedTasks) . "</div>\n"; // print_r($uploadedTasks, true) ?>



    <?php
        Modal::begin([
            'header' => Yii::$app->_L->get('lesson_initialize_spinner'),
            'toggleButton' => [ 'style' => 'display:none;','id' => 'modal_spinner'],
            'size' => "modal-sm"
        ]);
        echo '<div id="modalContent">';
        echo '<p style="text-align:center">';
        echo '<i style="font-size:24pt" class="fa fa-spinner fa-spin" aria-hidden="true"></i>';
        echo '<p/></div>';
        Modal::end();
    ?>



<ul class="nav nav-tabs nav-tabs-lesson">
  <li><a href="lesson_quick"><?= Yii::$app->_L->get('lesson_nav_tab_quick') ?></a></li>
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

          <?= $form->field($model, 'type')->hiddenInput(['value' => 'lesson'])->label(false); ?>


    <h4 style="margin-top: 0.8em; margin-bottom: 0.8em; "><?= Yii::$app->_L->get('lesson_tasks_title'); ?></h4>


    <input type='hidden' id='new_tasks' name='new_tasks' value=''>       
    <?php echo $form->field($model, 'numTasks',[])->hiddenInput(['value'=>1])->label(false); ?>

    <p>
                <?php
                if($model->description==""){
                    echo  Html::a(Yii::$app->_L->get('description_show_input_link')
                    , ['#']
                    , [ 'onclick' => '$(".field-lesson-description").show();
                                      $("#lesson-description").focus();
                                      $("#description_show_input_link").hide();
                                      return false;'
                        ,'id' => 'description_show_input_link'
                      ]
                    );
                }
                ?>

          <?php
            $this_css_display = 'none';
            if($model->description != ""){$this_css_display = 'inline';}
            echo $form->field($model, 'description'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ,'options' => ['style' => 'display:'.$this_css_display.';']
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('description_placeholder')
            , 'value' => $model->description
            ])
            ->label(Yii::$app->_L->get('description_label'))
            ;
            ?>

    </p>
    
    <div id="tasks">
        
        <div class='input-group task'>
        <label class="input-group-addon input-group-addon-tasks">

            <div class="dropdown task_type" data-task-type="text">
                <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"><?= Yii::$app->_L->get('lesson_tasks_type_text'); ?>
                <span class="caret"></span></button>
                <ul class="dropdown-menu">
                
                <?php
                
                    foreach($model->taskTypesOrder["lesson"] as $this_task_type){
                        
                    }
                
                ?>
                
                    <li><a data-task-type="text" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_text').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how-often" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_how-often').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="how-true" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_how-true').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="plus-minus" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_plus-minus').'&nbsp;&nbsp;'; ?></a></li>
                    <li><a data-task-type="info" href="#"
                     onclick="dropdown_task_type(this); return false;"><?php echo Yii::$app->_L->get('lesson_tasks_type_info').'&nbsp;&nbsp;'; ?></a></li>
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
    
    <div style="margin-top: 1.5em;">

    
          <?php
            $this_autofocus = false;
            //if($model->numStudents == "" & !$this_autofocus){$this_autofocus = true; $autofocus = true;}
            echo $form->field($model, 'numStudents'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('numStudents_placeholder')
            , 'value' => $model->numStudents
            , 'autofocus' => $this_autofocus
            ])
            ->label(Yii::$app->_L->get('numStudents_label'))
            ; ?>

          <?php
            $this_autofocus = false;
            //if($model->numTeamsize == "" & !$this_autofocus){$this_autofocus = true; $autofocus = true;}
            echo $form->field($model, 'numTeamsize'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('numTeamsize_placeholder')
            , 'value' => $model->numTeamsize
            , 'autofocus' => $this_autofocus
            ])
            ->label(Yii::$app->_L->get('numTeamsize_label'))
            ; ?>

    <p>
                <?php
                if($model->thinkingMinutes==""){
                    echo  Html::a(Yii::$app->_L->get('thinkingMinutes_show_input_link')
                    , ['#']
                    , [ 'onclick' => '$(".field-lesson-thinkingminutes").show();
                                      $("#lesson-thinkingminutes").focus();
                                      $("#thinkingMinutes_show_input_link").hide();
                                      return false;'
                        ,'id' => 'thinkingMinutes_show_input_link'
                      ]
                    );
                }
                ?>
    </p>

          <?php
            $autofocus = false;
            if($model->thinkingMinutes == ""){$autofocus = true;}
            $this_css_display = 'none';
            if($model->thinkingMinutes != ""){$this_css_display = 'inline';}
            echo $form->field($model, 'thinkingMinutes'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-lesson' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ,'options' => ['style' => 'display:'.$this_css_display.';']
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('thinkingMinutes_placeholder')
            , 'value' => $model->thinkingMinutes
            , 'autofocus' => $autofocus
            ])
            ->label(Yii::$app->_L->get('thinkingMinutes_label'))
            ;
            ?>



        <div class="form-group" style="margin-top: 2em; display: none;">
            <?php echo  Html::submitButton(Yii::$app->_L->get('lesson_btn_submit')
            , [
                'class' => 'btn btn-primary',
                'id'=>'lesson_btn_submit',
                'onclick' => 'if(lesson_exact_validate_tasks()!==false){
                    cm_spinner();this.form.submit();}'
                ]) ?>
        </div>
        
    </div>
       
    <?php ActiveForm::end(); ?>

</div>
<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;
var uploadedTasks = <?= json_encode($uploadedTasks); ?>;
var controller_lesson = <?= json_encode($model); ?>;
</script>

