<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\TeachersAsset;
TeachersAsset::register($this);


$this->title = Yii::$app->_L->get("teacher_title");

?>

    <?php
        foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
            $this_class = "alert ";
            if(substr($key, 0, 6) == "error_"){$this_class .= "alert-danger";}
            if(substr($key, 0, 8) == "warning_"){$this_class .= "alert-warning";}
            if(substr($key, 0, 8) == "success_"){$this_class .= "alert-success";}
            echo '<div class="'.$this_class.'">' . $message . "</div>\n";
        }
    ?>

<h3 style="margin-top: 0em; margin-bottom: 20px;">
    <?php echo Yii::$app->_L->get('teacher_poll_codes_title'); ?>
</h3>

<div class="keys-grid">
  <div class="row keys-grid-th">
    <div class="col-md-6 keys-grid-name"><?php echo Yii::$app->_L->get('teacher_poll_codes_participant'); ?></div>
    <div class="col-md-6 keys-grid-activationkey"><?php echo Yii::$app->_L->get('teacher_poll_codes_activationcode'); ?></div>
  </div>
  <?php 
    foreach($teachers as $teacher){
      echo '<div class="row keys-grid-td">\n';
      echo '<div class="col-md-6 keys-grid-name">'.$teacher['name'].'</div>\n';
      echo '<div class="col-md-6 keys-grid-activationkey">'.$teacher['activationkey'].'</div>\n';
      echo '</div>\n';
    }
  ?>
</div>