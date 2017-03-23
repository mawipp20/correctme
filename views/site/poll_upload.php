<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

$this->title = Yii::$app->_L->get("poll_title");

/* @var $this yii\web\View */
/* @var $model app\models\Lesson */
/* @var $form ActiveForm */
?>


<h3 style="margin-top: 0em; margin-bottom: 20px;"><?= Yii::$app->_L->get('poll_welcome') ?></h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }       
    ?>



<div class="Lesson">

<ul class="nav nav-tabs" style="margin-bottom: 20px;">
  <li><a href="poll_exact"><?= Yii::$app->_L->get('poll_nav_tab_exact') ?></a></li>
  <li class="active"><a href="poll_upload"><?= Yii::$app->_L->get('poll_nav_tab_upload') ?></a></li>
</ul>

    <h4 style="margin-top: 1.5em; margin-bottom: 1em; "><?= Yii::$app->_L->get('poll_upload_title'); ?></h4>

    <?php $form = ActiveForm::begin([
                'enableClientValidation'=>true,
                'validateOnChange'=>true,
                'validateOnBlur'=>true,
                'action' => ['lesson_exact'],
                'method' => "post", 
                'id' => 'lesson_form',
                'options' => [
                    'enctype'=>'multipart/form-data',
                    ],

    ]); ?>

    <?= $form->field($model, 'type')->hiddenInput(['value' => 'poll'])->label(false); ?>


<h3>

            
<label class="btn btn-default" for="lessonupload-lessonfile">

<?php echo Html::activeFileInput($model, 'lessonFile', ['style' => 'display:none;', 'onchange' => 'lesson_file_onchange(this)']); ?>
            
Datei auswählen
</label>
</h3>
<h5 class='' id="lessonFile-info" style="padding-top: 1em; padding-bottom: 1em;"></h5>
        <div class="form-group" style="margin-top: 1em;">
            <?= Html::button(Yii::$app->_L->get('lesson_upload_btn_submit'), 
                        ['class' => 'btn btn-primary', 'id'=>'lesson_btn_submit', 'onclick' => 'lesson_upload_on_submit(this);']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
