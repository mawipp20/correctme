<?php

use yii\helpers\Html;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

?>

    <div id="student_div" style="">
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
            <i class="fa fa-handshake-o" aria-hidden="true"></i>
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
    </div>
    <div id="teacher_div" style=''>    
        <h3 style="margin-top: 2em; margin-bottom: 1em;">
        <?= Yii::$app->_L->get('teacher_headline_poll_or_lesson') ?>
        </h3>

        <div class='well well-lg well-poll-or-lesson'
            onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/site/lesson'; ?>"'
        >
            <i class="fa fa-handshake-o" aria-hidden="true"></i>
            &nbsp;&nbsp;
            <?= Yii::$app->_L->get('teacher_btn_goto_lesson') ?>
        </div>
    
    
        <div class='well well-lg well-poll-or-lesson'
            onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/site/lesson_exact?lesson_type=poll&show_teacher_join'; ?>"'
        >
            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
            &nbsp;&nbsp;
            <?= Yii::$app->_L->get('teacher_btn_goto_poll') ?>
        </div>
    </div>




<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
