<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

use app\assets\TeacherPollResultAsset;
TeacherPollResultAsset::register($this);

use app\components\ResultsDisplay;

$this->title = Yii::$app->_L->get("teacher_title");


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
                foreach($teachersArr["students"] as $key => $val){
                    if($val["state"]=="prepared"){
                        $this_names[] = $val["name"];
                    }
                }
                echo count($this_names);
            ?>
            <span class="names" id="names_teachersArr_countInactive" style="display: none;">
                <br />
                <?php
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
            foreach($teachersArr["students"] as $key => $val){
                if($val["countStudents"] < $countStudentsLimit & $val["state"]!="prepared"){
                    $this_names[] = $val["name"];
                }
            }
            echo count($this_names);
            ?>
            <span class="names" id="names_teachersArr_countWithoutStudents" style="display: none;">
                <br />
                <?php
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
            foreach($teachersArr["students"] as $key => $val){
                if($val["countStudents"] >= $countStudentsLimit & $val["state"]!="prepared"){
                    $this_names[] = $val["name"];
                }
            }
            echo count($this_names);
            ?>
            <span class="names" id="names_teachersArr_countWithStudents" style="display: none;">
                <br />
                <?php
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


<!-- results for me -->
    <div class="results" id="my_results">
        <p><a href="teacher_results?print=my" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a></p>
        <?php
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
        if ($show_team_results){
            foreach($taskAnswers as $task){
                if($task["type"]=="text"){continue;}
                $t = "\n<div class='task'>\n";
                $t .= '<div class="task_text">'.$task["task_text"]."</div>\n";
                $t .= ResultsDisplay::get_distribution($lesson, $task, "my_");
                $q_my = round($task["my_sumAnswers"]/$task["my_countNumericAnswers"], 1);
                $q = round($task["sumAnswers"]/$task["countNumericAnswers"], 1);
    
                $line_gap = ($q_my - $q) * 4;
    
                $t .= ResultsDisplay::get_distribution($lesson, $task, "");
                $t .= "\n</div>\n";
                echo $t;              
            }
        }else{
            echo "<p class='msg_team_result_not_yet'>".$msg_team_result_not_yet."</p>";
        }
        ?>
</div>

<?php

    function teacher_poll_results_get_distribution($lesson, $task, $prefix, $line_gap = 0){
        
        $t = "";
        
        $this_color_code = "0,0,30";
        if($prefix == "my_"){
            $this_color_code = "0,30,0";
        }
        
        if(is_array($lesson->taskTypes[$task["type"]]) & $task[$prefix."countNumericAnswers"] > 0){
            
            $q = round($task[$prefix."sumAnswers"]/$task[$prefix."countNumericAnswers"], 1);
            $num_options = count($lesson->taskTypes[$task["type"]]);
            $opacity_count = 0;
            $width_sum = 0;
            $width_quota = 12;
            
            $width_max = 100 - $width_quota;
            
            $margin_color = "rgb(0,255,0)";
            if($line_gap < 0){$margin_color = "rgb(255,142,30)";}
            
            
            
            $t = "<div class='distribution' style='width:100%; border-top:".abs($line_gap)."em solid ".$margin_color.";'>\n";
            $t .= "<div class='one_distribution quota' style='width:".$width_quota."%;";
            $t .= "'>".$q."</div>";

                foreach($lesson->taskTypes[$task["type"]] as $task_type => $task_type_val){
                    $this_opacity = round($opacity_count / $num_options, 2);
                    $font_color = "white";
                    if($this_opacity < 0.6){
                        $font_color = "black";
                        $this_opacity = $this_opacity * 0.75;
                    }
                    $opacity_count++;
                    $val = 0;
                    $border_style = "";
                    if(isset($task[$prefix."distribution"][$task_type_val])){
                       $val = $task[$prefix."distribution"][$task_type_val];
                       $width = floor(round($val/$task[$prefix."countNumericAnswers"], 3)*$width_max);
                       $width_sum += $width;
                       
                       if($opacity_count == $num_options){
                        $width += ($width_max - $width_sum);$width_sum += ($width_max - $width_sum);}
                    }else{
                        continue;
                    }
                    $t .= "<div class='one_distribution'";
                    $t .= " style='width:".$width."%; color:".$font_color.";".$border_style;
                    $t .= " background: rgba(".$this_color_code.",".$this_opacity.");'>";
                    $t .= "<span class='one_distribution_val'>".$val."</span></div>";
                }
            $t .= "\n</div>\n";
        }elseif($prefix == "my_"){
            if(count($task['my_textAnswers'])>0){
                $t .= "<ul class='task_text_answers'>";
                foreach($task['my_textAnswers'] as $text){
                    $t .= "<li>".$text."</li>";
                }
                $t .= "</ul>";
            }else{
                $t .= "<div class='task_text_answers'>---</div>";
            }
        }
    
        return $t;
    }


?>

