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
    <?= Yii::$app->_L->get('teacher_questions') ?>
    </a> 
</div>

<div class="Teachers">

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                'action' => ['poll_start'],
                'method' => 'post',
                'id' => 'teachers_form',
    ]); ?>

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
            ]
            )->dropdownList([
                "60" => Yii::$app->_L->get('teacher_thinkingMinutes_hour'),
                "1440" => Yii::$app->_L->get('teacher_thinkingMinutes_day'),
                "10080" => Yii::$app->_L->get('teacher_thinkingMinutes_1week'),
                "20160" => Yii::$app->_L->get('teacher_thinkingMinutes_2week'),
                ])
            ->label(Yii::$app->_L->get('teacher_thinkingMinutes_label'))
            ;
            ?>


        <div id="div_teacher_submit" class="well" style="margin-top: 2em;">
        
            <?php echo  Html::submitButton(Yii::$app->_L->get('teacher_btn_just_me')
                , [
                'class' => 'btn btn-primary',
                'id'=>'teacher_btn_just_me',
                'onclick' => 'teachers_single_submit(this);'
                ]) ?>


            &nbsp;&nbsp;
            <?= Yii::$app->_L->get('teacher_btn_or') ?>
            &nbsp;&nbsp;

            <?php echo  Html::button(
                '<i class="fa fa-user-plus" aria-hidden="true"></i>'
                .'&nbsp;&nbsp;&nbsp;&nbsp;'
                .Yii::$app->_L->get('teacher_btn_team')
            , [
                'class' => 'btn btn-primary',
                'id'=>'teachers_btn_add_team',
                'onclick' => 'teachers_add_team();'
                ]) ?>

            <div id="team_info" style="display: none;">
                <h3 style=" margin-top:1em; margin-bottom:1em;">
                    <?= Yii::$app->_L->get('teacher_info_team_title') ?>
                </h3>
        
                <p><?= Yii::$app->_L->get('teacher_info_team_1') ?></p>
                <p><?= Yii::$app->_L->get('teacher_info_team_2') ?></p>
                <p><?= Yii::$app->_L->get('teacher_info_team_3') ?></p>
            </div>


        </div>

        <div id="team_div" style="display: none;">

            <div id="team_names">
                <div class='input-group teacher' style="">
                <label class="input-group-addon">
                    <?= Yii::$app->_L->get('teacher_team_member_label') ?>
                </label>
                <input type="text"
                     class="form-control teacher-name"
                     data-text-length="0"
                     oninput="teacherNameOnInput(this);"
                     placeholder="<?php echo Yii::$app->_L->get('teacher_team_member_name_placeholder');?>"
                     >
                <textarea id="teacher-name-textarea" rows="1" style="display: none;" class="form-control teacher-name" data-text-length="0"></textarea>
                </div>
            </div>    
            <div id="div_teachers_submit" class="" style="margin-top: 1em;">
                <?php 
                    $this_btn_label = '<span id="countTeachers"></span> '.Yii::$app->_L->get('teachers_team_submit');
                    echo  Html::button($this_btn_label
                    , [
                    'class' => 'btn btn-primary',
                    'id'=>'teachers_team_submit_id',
                    'onclick' => 'teachers_team_submit(this);'
                    ]) ?>
            </div>
        </div>



   
    <?php ActiveForm::end(); ?>

</div>
<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('teacher')); ?>;
</script>