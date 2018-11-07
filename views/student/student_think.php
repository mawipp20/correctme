<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

use app\components\MyHelpers;

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
        echo '<div class="modal-footer modal-footer-cm"><button type="button" class="btn btn-primary" data-dismiss="modal">'.Yii::$app->_L->get('gen_btn_close_dialog').'</button></div>';
        Modal::end();
        
        
        Modal::begin([
            'header' => "<span class='student-think-commit-dialog-modal-header-span'></span>",
            'id'=>'student_think_btn_commit',
            'toggleButton' => ['label' => Yii::$app->_L->get('student_think_finished_button')
                                ,'class' => 'btn btn-success display_none'
                                //,'style' => ["border-width" => "0px", "font-size" => "14px"]
                                ,'id' => 'student_think_btn_commit_toggle'
                                ],
        ]);
        echo "<div id='modalContent'><span class='student-think-commit-dialog-modal-content-span'></span></div>";
        echo '<div class="modal-footer modal-footer-cm">';
        echo '<button type="button" class="btn btn-default" data-dismiss="modal">'.Yii::$app->_L->get('gen_cancel').'</button>';
        echo  Html::button(Yii::$app->_L->get('student_think_finished_button')
            , [
                'class' => 'btn btn-success',
                'data-dismiss' => 'modal',
                'id'=>'student_think_btn_commit_confirmed',
                //'onclick' => 'window.location.href = "'.Yii::$app->getUrlManager()->getBaseUrl().'/student/commit_single',
                ]);
        echo '</div>';
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

/** need to build a string from taskType - values to keep the order in js */
foreach($lesson->taskTypes as $key => $val){
    if(isset($lesson->taskTypes[$key]["values"])){
        $lesson->taskTypes[$key]["values_string"] = implode("#|#", array_keys($lesson->taskTypes[$key]["values"]));
    }
}

?>

    <?php
        Modal::begin([
            'header' => Yii::$app->_L->get('gen_complete'),
            'toggleButton' => [ 'style' => 'display:none;','id' => 'modal_spinner'],
            'size' => "modal-sm"
        ]);
        echo '<div id="modalContent">';
        echo '<p style="text-align:center">';
        echo '<i style="font-size:24pt" class="fa fa-spinner fa-spin" aria-hidden="true"></i>';
        echo '<p/></div>';
        Modal::end();
    ?>



<script>
var _L = <?= json_encode(Yii::$app->_L->get('student_think')); ?>;
var task_types = <?= json_encode($lesson->taskTypes); ?>;
function cmConfigO(){

    this.restcorrectmeBaseUrl = <?= MyHelpers::cmConfigJsValue("restcorrectmeBaseUrl") ?>;
    this.restcorrectmePath = <?= MyHelpers::cmConfigJsValue("restcorrectmePath") ?>;

    this.displayThinkingMinutes = <?= MyHelpers::cmConfigJsValue("cmConfig_displayThinkingMinutes") ?>;
    this.displayTaskNavSecond = <?= MyHelpers::cmConfigJsValue("cmConfig_displayTaskNavSecond") ?>;
    this.displayTaskLabelNum = <?= MyHelpers::cmConfigJsValue("cmConfig_displayTaskLabelNum") ?>;
    this.displayBtnHelp = <?= MyHelpers::cmConfigJsValue("cmConfig_displayBtnHelp") ?>;
    this.studentRedirectAfterLastAnswer = <?= MyHelpers::cmConfigJsValue("cmConfig_studentRedirectAfterLastAnswer") ?>;
    this.taskFinishedButtonMoveOn = <?= MyHelpers::cmConfigJsValue("cmConfig_taskFinishedButtonMoveOn") ?>;

}
var cmConfig = new cmConfigO();
</script>

