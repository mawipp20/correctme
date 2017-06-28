<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

//print_r(URL::base()); exit;


use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\TeacherPollResultAsset;
TeacherPollResultAsset::register($this);

use app\components\ResultsDisplay;

$this->title = Yii::$app->_L->get("teacher_title");

?>

<style>
.one_distribution{
    display: inline-block;
    padding: 0.3em;
    text-align: center;
    border-right: 2px solid white;
    b/ackground-image: url(<?php echo  URL::base(); ?>/images/plus.gif);
    b/ackground-repeat: repeat;
    b/ackground-position: right;
}
</style>

<?php


//print_r($lesson);
//print_r($teachersArr);
//print_r($numStudents);
//print_r($taskAnswers);

//exit();



$show_team_results = false;
if ($lesson["poll_type"] != "single"
            & $numStudents["mine"] >= $countStudentsLimit
            & $teachersArr["withStudents"] >= 3
            ){
            $show_team_results = true;
}
$msg_team_result_not_yet = "";
if($teachersArr["withStudents"] < 3){
    $msg_team_result_not_yet .= Yii::$app->_L->get('teacher_poll_results_not_enough_teachers')." ";
}
if($numStudents["mine"] < $countStudentsLimit){
    if($msg_team_result_not_yet != ""){$msg_team_result_not_yet .= "<br /><br />";}
    $m = Yii::$app->_L->get('teacher_poll_results_not_enough_students');
    $msg_team_result_not_yet .= str_replace("#limit#", $countStudentsLimit, $m);
}

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

 	5rik6u

*/ ?>

    <table class="poll_results_teachers">
<?php if ($lesson["poll_type"] != "single"): ?>
      <tr>
        <td class="prompt" style="font-weight:  bold;">
            <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_title'); ?>
        </td><td class="data">
        <?php
            if($lesson["poll_type"] == "team"){
                echo count($teachersArr["students"]);
            }
        ?>            
        </td>
      </tr>
<?php endif; ?>
<?php if ($lesson["poll_type"] == "names"): ?>
   
      <tr>
        <td class="prompt">
        <a href="#" onclick="$('#names_teachersArr_countInactive').toggle();">
            <?php 
                $msg = Yii::$app->_L->get('teacher_poll_results_teachersArr_countInactive');
                echo $msg;
            ?>
        </a>
        </td><td class="data">
            <?php
                $this_names = array();
                $count_anonymous = 0;
                $count_prepared = 0;
                foreach($teachersArr["students"] as $key => $val){
                    if($val["state"]=="prepared"){
                        $count_prepared++;
                        if($val["name"]==""){
                            $count_anonymous++;
                        }else{
                            $this_names[] = $val["name"];
                        }
                    }
                }
                echo $count_prepared;
            ?>
            <span class="names" id="names_teachersArr_countInactive" style="display: none;">
                <br />
                <?php
                if($count_anonymous > 0){
                    array_unshift($this_names, Yii::$app->_L->get('teacher_poll_results_anonymous_teachers')." (".$count_anonymous.")");
                }
                echo implode(", ", $this_names);
                ?>
            </span>
        </td>
      </tr>
    
      <tr>
        <td class="prompt">
        <a href="#" onclick="$('#names_teachersArr_countWithoutStudents').toggle();">
            <?php 
                $msg = Yii::$app->_L->get('teacher_poll_results_teachersArr_countWithoutStudents');
                $msg = str_replace("#countStudentsLimit#", $countStudentsLimit, $msg);
                echo $msg;
            ?>
        </a>
        </td><td class="data">
            <?php
            $this_names = array();
            $count_anonymous = 0;
            $count_active = 0;
            foreach($teachersArr["students"] as $key => $val){
                if($val["countStudents"] < $countStudentsLimit & $val["state"]!="prepared"){
                    $count_active++;
                    if($val["name"]==""){
                        $count_anonymous++;
                    }else{
                        $this_names[] = $val["name"];
                    }
                }
            }
            echo $count_active;
            ?>
            <span class="names" id="names_teachersArr_countWithoutStudents" style="display: none;">
                <br />
                <?php
                if($count_anonymous > 0){
                    array_unshift($this_names, Yii::$app->_L->get('teacher_poll_results_anonymous_teachers')." (".$count_active.")");
                }
                echo implode(", ", $this_names);
                ?>
            </span>
        </td>
      </tr>
<?php endif; ?>    
<?php if ($lesson["poll_type"] == "names" | $lesson["poll_type"] == "team"): ?>
      <tr>
        <td class="prompt">
        <a href="#" onclick="$('#names_teachersArr_countWithStudents').toggle();">
            <?php 
                $msg = Yii::$app->_L->get('teacher_poll_results_teachersArr_countWithStudents');
                $msg = str_replace("#countStudentsLimit#", $countStudentsLimit, $msg);
                echo $msg;
            ?>
        </a>
        </td><td class="data">
            <?php
            $this_names = array();
            $count_anonymous = 0;
            $count_active = 0;
            foreach($teachersArr["students"] as $key => $val){
                if($val["countStudents"] >= $countStudentsLimit & $val["state"]!="prepared"){
                    $count_active++;
                    if($val["name"]==""){
                        $count_anonymous++;
                    }else{
                        $this_names[] = $val["name"];
                    }
                }
            }
            echo $count_active;
            ?>
            <span class="names" id="names_teachersArr_countWithStudents" style="display: none;">
                <br />
                <?php
                if($count_anonymous > 0){
                    array_unshift($this_names, Yii::$app->_L->get('teacher_poll_results_anonymous_teachers')." (".$count_active.")");
                }
                echo implode(", ", $this_names);
                ?>
            </span>
        </td>
      </tr>
<?php endif; ?>
<?php if ($lesson["poll_type"] == "names"): ?>
      <tr>
        <td class="prompt">
            <?php echo Yii::$app->_L->get('teacher_poll_results_teachersArr_countAll'); ?>
        </td><td class="data">
            <?php echo count($teachersArr["students"]); ?>
        </td>
      </tr>
<?php endif; ?>

      <tr class="margin-top">
        <td class="prompt">
            <?php echo Yii::$app->_L->get('teacher_poll_results_countMyStudents'); ?>
        </td><td class="data">
            <?php echo $teachersArr["students"][$teacher->id]["countStudents"]; ?>
        </td>
      </tr>
    </table>









<!-- tabs for team results: show even if not yet accessible  -->

<?php if ($lesson["poll_type"] != "single"): ?>
    <ul class="nav nav-tabs" id="result-nav-tabs">
      <li class="active"><a href="#" onclick="result_tabs_click(this, 'my_results'); return false;">
      <?= Yii::$app->_L->get('teacher_poll_results_tab_my_results') ?></a></li>
      
      <li><a href="#" onclick="result_tabs_click(this, 'all_results'); return false;">
      <?= Yii::$app->_L->get('teacher_poll_results_tab_all_results') ?></a></li>
      
      <li><a href="#" onclick="result_tabs_click(this, 'mixed_results'); return false;">
      <?= Yii::$app->_L->get('teacher_poll_results_tab_mixed_results') ?></a></li>
    </ul>
<?php endif; ?>


<?php

$btn_print = '


        <p style="font-size:large; padding-top:2em;margin-bottom:0em; text-align: center;">
        <a class="btn_print" href="#" onclick=\'
            window.open("teacher_results?print=prefix_var&how=printer",null,"height=800,width=600,status=yes,toolbar=no,menubar=no,location=no");return false;
            \'>
        <i class="fa fa-print" aria-hidden="true"></i></a>
        &nbsp;&nbsp;&nbsp;
        <a class="btn_print" href="teacher_results?print=prefix_var&how=pdf" target="_blank">
        pdf</a>
        </p>
        
        
';

?>

<!-- results for me -->
    <div class="results" id="my_results">
        <?php

        echo str_replace("prefix_var", "my", $btn_print);
        
        foreach($taskAnswers as $task){
            $t = "\n<div class='task'>\n";
            $t .= '<div class="task_text">'.$task["task_text"];
            $t .= "<span class='num_answers'>(".$task["my_countNumericAnswers"].")</span></div>\n";
            $t .= ResultsDisplay::get_distribution($lesson, $task, "my_");
            $t .= "\n</div>\n";
            echo $t;              
        }
        ?>
    </div>

<!-- results for the team -->
<div class="results" id="all_results" style="display: none;">
        <?php
        echo str_replace("prefix_var", "all", $btn_print);
        if ($show_team_results){
            foreach($taskAnswers as $task){
                if($task["type"]=="text"){continue;}
                $t = "\n<div class='task'>\n";
                $t .= '<div class="task_text">'.$task["task_text"];
                $t .= "<span class='num_answers'>(".$task["countNumericAnswers"].")</span></div>\n";
                $t .= ResultsDisplay::get_distribution($lesson, $task, "");
                $t .= "\n</div>\n";
                echo $t;              
            }
        }else{
            echo "<p class='msg_team_result_not_yet'>".$msg_team_result_not_yet."</p>";
        }
        ?>
</div>

<!-- results compared -->
<div class="results" id="mixed_results" style="display: none;">
        <?php
        echo str_replace("prefix_var", "compare", $btn_print);
        if ($show_team_results){
            foreach($taskAnswers as $task){
                if($task["type"]=="text"){continue;}
                $t = "\n<div class='task'>\n";
                $t .= '<div class="task_text">'.$task["task_text"]."</div>\n";
                $t .= ResultsDisplay::get_distribution($lesson, $task, "my_");
                $q_my = round($task["my_sumAnswers"]/$task["my_countNumericAnswers"], 1);
                $q = round($task["sumAnswers"]/$task["countNumericAnswers"], 1);
    
                $line_gap = ($q_my - $q) * 4;
    
                $t .= ResultsDisplay::get_distribution($lesson, $task, "", $line_gap);
                $t .= "\n</div>\n";
                echo $t;              
            }
        }else{
            echo "<p class='msg_team_result_not_yet'>".$msg_team_result_not_yet."</p>";
        }
        ?>
</div>

