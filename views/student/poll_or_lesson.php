<?php

use yii\helpers\Html;

use app\assets\AppAsset;
AppAsset::register($this);

    
    if(Yii::$app->params["correctmeHasPoll"]){
        include_once(Yii::$app->basePath.'/views/components/_inc_poll_or_lesson_pollButtons.php');
    }
    
    
    if(Yii::$app->params["correctmeHasLesson"]){
        include_once(Yii::$app->basePath.'/views/components/_inc_poll_or_lesson_lessonButtons.php');
    }

    if(Yii::$app->params["correctmeHasPoll"]){
        include_once(Yii::$app->basePath.'/views/components/_inc_poll_explained.php');
    }
    
    ?>

        

<script>var _L_lesson = <?= json_encode(Yii::$app->_L->get('lesson')); ?>;</script>

