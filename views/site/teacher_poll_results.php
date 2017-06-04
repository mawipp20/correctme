<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\TeacherPollResultAsset;
TeacherPollResultAsset::register($this);


$this->title = Yii::$app->_L->get("teacher_title");


//print_r($lesson);
//print_r($teachersArr);
//print_r($numStudents);
//print_r($taskAnswers);

//exit();


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

<div class="alert alert-success" style="margin-top: 0em; margin-bottom: 20px; font-size: large;">
    <?php
        $msg_success = Yii::$app->_L->get('teacher_poll_results_title');
        $start_date = new DateTime($lesson->insert_timestamp);
        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->add(new DateInterval('PT' . $lesson->thinkingMinutes . 'M'));
        $deadline_results = new DateTime();
        $deadline_results->add(new DateInterval('PT'.($lesson->thinkingMinutes + 10080).'M'));
        $msg_success = str_replace('#start_date#', Yii::$app->formatter->asDate($start_date), $msg_success); 
        $msg_success = str_replace('#deadline#', Yii::$app->formatter->asDate($deadline), $msg_success); 
        $msg_success = str_replace('#deadline_results#', Yii::$app->formatter->asDate($deadline_results), $msg_success); 
        $msg_success = str_replace('#lesson_title#', '<b>'.$lesson->title.'</b>', $msg_success); 
        echo $msg_success;      
    ?>
</div>

<?php /**

        $teachersArr = array("countAll"=>count($teachers));
        $teachersArr["countActive"] = 0;
        $teachersArr["withStudents"] = 0;
        $teachersArr["students"] = array();

teacher_poll_results_teachersArr_countInactive = ... nicht aktiv
teacher_poll_results_teachersArr_countActive = ... aktiv mit weniger als 5 befragten Schüler/innen
teacher_poll_results_teachersArr_countWithStudents = ... mit mindestens 5 befragten Schüler/innen

*/ ?>

<table id="poll_results_teachers">
  <tr>
    <td class="prompt" style="font-weight:  bold;">
        <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_title'); ?>
    </td><td class="data">
        
    </td>
  </tr>
  
  <tr>
    <td class="prompt">
        <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_countAll'); ?>
    </td><td class="data">
        <?php echo $teachersArr["countAll"]; ?>
    </td>
  </tr>

  <tr>
    <td class="prompt">
        <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_countInactive'); ?>
    </td><td class="data">
        <?php echo $teachersArr["countAll"] - $teachersArr["countActive"]; ?>
    </td>
  </tr>

  <tr>
    <td class="prompt">
    <a href="#" onclick="$('#names_teachersArr_countWithoutStudents').toggle();">
        <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_countWithoutStudents'); ?>
    </a>
    </td><td class="data">
        <?php echo $teachersArr["countActive"]  - $teachersArr["withStudents"]; ?>
        <span class="names" id="names_teachersArr_countWithoutStudents" style="display: none;">
            <br />
            <?php
            $this_names = array();
            foreach($teachersArr["students"] as $key => $val){
                if($val["countStudents"] < $countStudentsLimit  & $val["name"] != "template_do_not_display"){
                    $this_names[] = $val["name"];
                }
            }echo implode(", ", $this_names);
            ?>
        </span>
    </td>
  </tr>

  <tr>
  <tr>
    <td class="prompt">
    <a href="#" onclick="$('#names_teachersArr_countWithStudents').toggle();">
        <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_countWithStudents'); ?>
    </a>
    </td><td class="data">
        <?php echo $teachersArr["withStudents"]; ?>
        <span class="names" id="names_teachersArr_countWithStudents" style="display: none;">
            <br />
            <?php
            $this_names = array();
            foreach($teachersArr["students"] as $key => $val){
                if($val["countStudents"] >= $countStudentsLimit  & $val["name"] != "template_do_not_display"){
                    $this_names[] = $val["name"];
                }
            }echo implode(", ", $this_names);
            ?>
        </span>
    </td>
  </tr>

  <tr class="margin-top">
    <td class="prompt">
        <?php echo Yii::$app->_L->get('teacher_poll_results_countMyStudents'); ?>
    </td><td class="data">
        <?php echo $teachersArr["students"][$teacher->id]["countStudents"]; ?>
    </td>
  </tr>
</table>





  <?php 
  /**
    foreach($teachers as $teacher){
        $css_class_initiator = "";
        if($teacher['name'] == $initiator->name){
            $css_class_initiator = " initiator";
        }
        echo '<div class="row tr'.$css_class_initiator.'">';
        echo '<div class="col-md-3 name">'.$teacher['name'].'</div>';
        echo '<div class="col-md-9 activationkey">'.$teacher['activationkey'].'</div>';
        echo '</div>';
    }
    */
  ?>
