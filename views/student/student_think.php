<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\StudentAsset;
StudentAsset::register($this);


$this->title = _L("student_think_title");
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="think">

    <?= Html::hiddenInput('startKey', Yii::$app->getSession()->get("startKey"))  ?>
    <?= Html::hiddenInput('studentKey', Yii::$app->getSession()->get("studentKey"))  ?>



    <?php
        Modal::begin([
            'header' => _L('student_think_messageHelpHeader'),
            'id'=>'student_think_btn_help',
            'toggleButton' => ['label' => 'not displayed'
                                ,'class' => 'display_none'
                                ,'id' => 'student_think_help_message_btn_toggle'
                                ],
        ]);
        echo "<div id='modalContent'>"._L('student_think_messageHelpText')."</div>";
        echo '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">'._L('gen_btn_close_dialog').'</button></div>';
        Modal::end();
    ?>



    <div id="displayTasks"></div>
    <div id="taskNav_first" class="text-center"></div>
    <div class="lessonInfo" id="lessonInfo"></div>
    <div id="taskNav_second"></div>

</div>

<script>var _L = <?= json_encode(_L('_L_student_think')); ?>;</script>

