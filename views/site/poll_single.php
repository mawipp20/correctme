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

<h3 style="margin-top: 0em; margin-bottom: 20px;">
    <?php 
    if($model->title != ""){
        echo $model->title;
    }else{
        echo Yii::$app->_L->get('student_join_poll_title');
    }
    ?>
</h3>

<div class="" style="margin-top: 0em; margin-bottom: 20px;">

    
    <a class='btn btn-default' href="download_questions">
        <i class="fa fa-save" aria-hidden="true"></i>
        &nbsp;&nbsp;
        <?= $model->numTasks ?>
        <?= Yii::$app->_L->get('poll_save_questions') ?>
        <?= Yii::$app->_L->get('gen_save') ?>
    </a> 
</div>

<div class="Teachers">

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                'action' => ['teacher_join_poll'],
                'method' => 'post',
                'id' => 'teachers_form',
    ]); ?>


    <?= $form->field($model, 'poll_type')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'startKey')->hiddenInput()->label(false); ?>   

          <?php
            echo $form->field($teacher, 'name'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-teacher' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ]
            )->textInput([
            'placeholder'=>Yii::$app->_L->get('teacher_my_name_placeholder'),
            'value' => $teacher->name,
            'autofocus' => true,
            ])
            ->label(Yii::$app->_L->get('teacher_my_name_label'))
            ; ?>

          <?php
            echo $form->field($model, 'thinkingMinutes'
            , [
            'labelOptions' => [ 'class' => 'input-group-addon input-group-addon-teacher' ]
            ,'template' => "<div class='input-group input-group-lesson'>{label}\n{input}\n{hint}\n{error}</div>"
            ,'enableClientValidation' => false
            ]
            )->dropdownList([
                "today" => Yii::$app->_L->get('teacher_thinkingMinutes_today'),
                "end_of_this_week" => Yii::$app->_L->get('teacher_thinkingMinutes_end_of_this_week'),
                "end_of_next_week" => Yii::$app->_L->get('teacher_thinkingMinutes_end_of_next_week'),
                "end_of_week_after_next" => Yii::$app->_L->get('teacher_thinkingMinutes_end_of_week_after_next'),
                ])
            ->label(Yii::$app->_L->get('teacher_thinkingMinutes_label'))
            ;
            ?>

            <?php
            echo  Html::button(Yii::$app->_L->get('teacher_btn_just_me')
            , [
            'class' => 'btn btn-primary',
            'id'=>'teacher_btn_just_me',
            'onclick' => 'teachers_submit_single();'
            ]);
            ?>

   
    <?php ActiveForm::end(); ?>

</div>
<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('teacher')); ?>;
</script>