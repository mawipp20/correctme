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
    if($lesson->title != ""){
        echo $lesson->title;
    }else{
        echo Yii::$app->_L->get('student_join_poll_title');
    }
    ?>
</h3>

<div class="" style="margin-top: 0em; margin-bottom: 20px;">

    
    <a class='btn btn-default' href="download_questions">
        <i class="fa fa-save" aria-hidden="true"></i>
        &nbsp;&nbsp;
        <?php echo $lesson->numTasks ?>
        <?php echo Yii::$app->_L->get('poll_save_questions') ?>
        <?php echo Yii::$app->_L->get('gen_save') ?>
    </a> 
</div>

<div class="Teachers">

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>false,
                'action' => ['teacher_poll_codes'],
                'method' => 'post',
                'id' => 'teachers_form',
    ]); ?>


    <input type='hidden' id='teachers_collected' name='teachers_collected' value=''>       
    <?php echo $form->field($lesson, 'poll_type')->hiddenInput()->label(false); ?>
    <?php echo $form->field($teacher, 'name')->hiddenInput(["value"=>"template_teacher"])->label(false); ?>
    

          <?php
          /**
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
            ;
            */
            ?>

          <?php
            echo $form->field($lesson, 'thinkingMinutes'
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
                "end_of_week_and_90_days" => Yii::$app->_L->get('teacher_thinkingMinutes_end_of_week_and_90_days'),
                ])
            ->label(Yii::$app->_L->get('teacher_thinkingMinutes_label'))
            ;
            ?>

                                                        <h3 style="display: none;">
                                                            <?php echo Yii::$app->_L->get('teachers_info_team_title') ?>
                                                        </h3>


            <div id='teacher_info_team' style="margin-top: 2em; margin-bottom: 3em;">
                <p><?php echo Yii::$app->_L->get('teacher_info_team') ?></p>
            </div>
            


            <div id="div_team_without_names">
                                                        <div id='teacher_info_team_with_names' style="display: none;">
                                                            <p>
                                                            <span style="">
                                                                <?php echo Yii::$app->_L->get('teacher_info_team_without_names_title') ?>
                                                            </span>
                                                                <?php echo Yii::$app->_L->get('teacher_info_team_without_names') ?>
                                                            </p>
                                                        </div>
                <?php 
                echo  Html::button(
                Yii::$app->_L->get('teacher_info_team_without_names_btn')
                , [
                    'class' => 'btn btn-primary',
                    'id'=>'teachers_btn_without_names',
                    'onclick' => 'teachers_submit_team();'
                    ])
                    ?>
            </div>

                                                        <div id='teacher_info_team_with_names' style="margin-top:  3em; display: none;">
                                                            <p>
                                                            <span style="">
                                                                <?php echo Yii::$app->_L->get('teacher_info_team_with_names_title') ?>
                                                            </span>
                                                                <?php echo Yii::$app->_L->get('teacher_info_team_with_names') ?>
                                                            </p>
                                            
                                                        <?php 
                                                        echo  Html::button(
                                                            '<i class="fa fa-user-plus" aria-hidden="true"></i>'
                                                            .'&nbsp;&nbsp;&nbsp;&nbsp;'
                                                            .Yii::$app->_L->get('teacher_info_team_with_names_btn')
                                                        , [
                                                            'class' => 'btn btn-primary',
                                                            'id'=>'teachers_btn_add_team',
                                                            'onclick' => 'teachers_add_names();'
                                                            ])
                                                            ?>
                                                        </div>


        <div id="team_div" style="display: none; margin-top: 1em;">

            <div id="team_names">
                <div class='input-group teacher' style="">
                <label class="input-group-addon">
                    <?php echo Yii::$app->_L->get('teacher_team_member_label') ?>
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
                    'onclick' => 'teachers_submit_names();'
                    ]) ?>
            </div>
        </div>

            


   
    <?php ActiveForm::end(); ?>

</div>
<script>
var _L_lesson = <?php echo json_encode(Yii::$app->_L->get('teacher')); ?>;
</script>