<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);


$this->title = Yii::$app->_L->get("poll_title");

$show_teacher_join = false;
        
?>

    <h2 style="margin-bottom: 20px;"><?= Yii::$app->_L->get('gen_teacher') ?></h2>


<div id='switch_activate_create' class='well well-lg  well-correctme-as-button'
    style="margin-top: 1em; margin-bottom: 3em;"
    onclick='window.location.href = "lesson_exact"'
>
    <?= Yii::$app->_L->get('lesson_switch_activate_create') ?>
</div>



<div id='activate_poll' style="">

        <!-- activate poll -->
        
        <?php $form1 = ActiveForm::begin([
                    'action' => ['teacher_join_poll'],
                    'method' => "get",    
                    'validateOnChange'=>true,
                    'validateOnBlur'=>false,
        ]);        
        ?>

          <?= $form1->field($lesson, 'type')->hiddenInput(['value' => 'poll'])->label(false); ?>

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

<script>
var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;
var _L_poll = <?= json_encode(Yii::$app->_L->get('poll')); ?>;
var _L_student_think = <?= json_encode(Yii::$app->_L->get('student_think')); ?>;
var controller_lesson = <?= json_encode($lesson); ?>;
</script>

<?php //echo '<div class="alert alert-waring">' . print_r($uploadedTasks) . "</div>\n"; // print_r($uploadedTasks, true) ?>


<!-- Lesson -->
