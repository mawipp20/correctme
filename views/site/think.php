<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\ThinkAsset;
ThinkAsset::register($this);


$this->title = Yii::$app->_L->get("THINK_TITLE");
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="think">

    <?= Html::hiddenInput('startKey', Yii::$app->getSession()->get("startKey"))  ?>
    <?= Html::hiddenInput('teacherKey', Yii::$app->getSession()->get("teacherKey"))  ?>

<div class="page-header">
  <h1>

    <?php
        Modal::begin([
            'header' => '<h2>'.Yii::$app->_L->get('think_dialog_startKey_info_header').'</h2>',
            'toggleButton' => ['label' => '<h3><i class="fa fa-sign-in" aria-hidden="true"></i><h3>'
                                ,'style' => 'background-color: transparent; border-width: 0px; color: rgb(150,150,150);'
                                ],
            //'clientOptions' => ['style' => 'background-color:orange;'],
        ]);
    
        echo "<div id='modalContent'><h4>".Yii::$app->_L->get('think_dialog_startKey_info_text')."</h4></div>";
        echo '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">'.Yii::$app->_L->get('gen_btn_close_dialog').'</button></div>';
        Modal::end();
    ?>

<?= $model->startKey ?> 

    <div style="float:right;">

    <?php
        Modal::begin([
            'header' => ''.Yii::$app->getSession()->get("teacherKey").'',
            'toggleButton' => ['label' => Yii::$app->_L->get('think_btn_show_teacher_retrieve_session_key')
                                ,'class' => 'btn btn-default'
                                ],
        ]);
    
        echo "<div id='modalContent'><h4>".Yii::$app->_L->get('think_dialog_teacherKey_info_text')."</h4></div>";
        echo '<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">'.Yii::$app->_L->get('gen_btn_close_dialog').'</button></div>';
        Modal::end();
    ?>
    
        <?= Html::button(Yii::$app->_L->get('think_btn_submit'), [
            'class' => 'btn btn-primary'
            ,'onclick' => 'getThinkingStudents();'
            ]) ?>
            
    </div><div style="clear: right;"></div>

  </h1>
</div>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            echo '<div class="alert alert-danger">' . $message . "</div>\n";
        }
    ?>


    <div id="studentRows"></div>
    
    <p>You have achieved 25 points out of 50 (<span class="pie">15</span>%)</p>
    
</div>

