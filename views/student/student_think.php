<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\StudentAsset;
StudentAsset::register($this);


$this->title = Yii::$app->_L->get("student_think_title");
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="think">

    <?= Html::hiddenInput('startKey', Yii::$app->getSession()->get("startKey"))  ?>
    <?= Html::hiddenInput('studentKey', Yii::$app->getSession()->get("studentKey"))  ?>



    <?php
        Modal::begin([
            'header' => Yii::$app->_L->get('student_think_messageHelpHeader'),
            'id'=>'student_think_btn_help',
            'toggleButton' => ['label' => 'not displayed'
                                ,'class' => 'display_none'
                                ,'id' => 'student_think_help_message_btn_toggle'
                                ],
        ]);
        echo "<div id='modalContent'>".Yii::$app->_L->get('student_think_messageHelpText')."</div>";
        echo '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">'.Yii::$app->_L->get('gen_btn_close_dialog').'</button></div>';
        Modal::end();
    ?>



    <div id="displayTasks">
        <div id="taskNavWait"><i class="fa fa-circle-o-notch fa-spin"></i></div>
    </div>
    <div id="taskNav_first" class="row"></div>
    <div class="lessonInfo" id="lessonInfo"></div>
    <div id="taskNav_second"></div>

</div>

<?php

$restcorrectmeUrl = 'http://localhost/restcorrectme/web/student/think';
if($_SERVER['HTTP_HOST'] == 'zelon.de'){
    $restcorrectmeUrl = 'http://zelon.de/restcorrectme/web/student/think';
}


?>

<script>
var _L = <?= json_encode(Yii::$app->_L->get('student_think')); ?>;
function cmConfigO(){
    this.restcorrectmeUrl = '';
    this.displayThinkingMinutes = false;
    this.displayTaskNavSecond = false;
    this.displayTaskLabelNum = false;
    this.displayBtnHelp = false;
    this.studentRedirectAfterLastAnswer = true;
    this.taskFinishedButtonMoveOn = true;
    this.restcorrectmeUrl = '<?= $restcorrectmeUrl ?>';
}
var cmConfig = new cmConfigO();
</script>

