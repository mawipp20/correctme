<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

?>

<style>
.well-poll-or-lesson{
    color: darkblue;
    margin-bottom: 2em;
    font-size: large;
}
.well-poll-or-lesson:hover{
    background-color:  rgb(51,122,183);
    color: white;
    margin-bottom: 2em;
    cursor: pointer;
    font-size: large;
}
</style>

    <h3 style="margin-top: 0.5em; margin-bottom: 1em;">
    <?= Yii::$app->_L->get('student_headline_poll_or_lesson') ?>
    </h3>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>


    <div class='well well-lg well-poll-or-lesson'
        onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/student/student_join'; ?>"'
    >
        <i class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
        &nbsp;
        <?= Yii::$app->_L->get('student_btn_goto_lesson') ?>
    </div>


    <div class='well well-lg well-poll-or-lesson'
        onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/student/student_join_poll'; ?>"'
    >
        <i class="fa fa-question-circle-o" aria-hidden="true"></i>
        &nbsp;
        <?= Yii::$app->_L->get('student_btn_goto_poll') ?>
    </div>




<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
