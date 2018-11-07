<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\LessonAsset;
LessonAsset::register($this);

?>

</style>

    <form id="start_lesson_form" name="start_lesson_form" method="get" action="">
    <input type="hidden" id="lesson_type" name="lesson_type" value="">

    <h3 style="margin-top: 0.0em; margin-bottom: 1em;">
    <?= Yii::$app->_L->get('teacher_headline_poll_or_lesson') ?>
    </h3>


    <div class='well well-lg well-correctme-as-button'
        onclick='$("#lesson_type").val("lesson");
                 $("#start_lesson_form").attr("action", "lesson");
                 $("#start_lesson_form").submit();
                 '
    >
        <i class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i>
        &nbsp;&nbsp;
        <?= Yii::$app->_L->get('teacher_btn_goto_lesson') ?>
    </div>


    <div class='well well-lg well-correctme-as-button'
        onclick='$("#lesson_type").val("poll");
                 $("#start_lesson_form").attr("action", "lesson_exact");
                 $("#start_lesson_form").submit();
                 '
    >
        <i class="fa fa-question-circle-o" aria-hidden="true"></i>
        &nbsp;&nbsp;
        <?= Yii::$app->_L->get('teacher_btn_goto_poll') ?>
    </div>


<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

<!-- Lesson -->
