<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>correctme print</title>
  </head>
  <style>
        body{
            font-family: Helvetica;
        }
        @media print{
            .print_hide{
                display: none;
            }
        }
        td{
            text-align: left;
        }
        p{
            margin-top: 0em;
            margin-bottom: 0em;
        }
        .task_text{
            font-size: large;
            padding:0.3em;
            padding-top:1em;
            padding-left:0em;
            margin-top: 1em;
            margin-bottom: 0.3em;
        }
        .task_type_title{
            font-weight: bold;
            padding-top:1em;
            padding-bottom:0.5em;
        }
        .task_explanation{
            font-style: italic;
            font-size: smaller;   
        }
        .text_answers{
            padding:0.3em;
        }
        .distribution{
            text-align: right;
            padding: 0px;
            padding-left: 1em;
            padding-bottom: 0.5em;
            font-size: 10pt;
            font-size: small;
            color: rgb(60,60,60);
        }
        button{
            padding:1em;
            font-size: large;
            background: #ff0000;
            color: white;
            border-radius: 6px;
        }
  </style>
  <body>
  
    <h1 class="print_hide" style="text-align: center; margin-top: 0em;">
    <button onclick="window:print();"><?= Yii::$app->_L->get('gen_print');?></button>
    </h1>


<?php

        $t = "<h1>".$lesson->title;
        if($print == "my"){$t .= " (".Yii::$app->_L->get('teacher_poll_results_tab_my_results').")";}
        if($print == "all"){$t .= " (".Yii::$app->_L->get('teacher_poll_results_tab_all_results').")";}
        if($print == "compare"){$t .= " (".Yii::$app->_L->get('teacher_poll_results_tab_mixed_results').")";}
        $t .= "</h1>";
        
        /**
                    <?php echo Yii::$app->_L->get('teacher_poll_results_countMyStudents'); ?>
        </td><td class="data">
            <?php echo $numStudents["mine"]; ?>
*/
        
        $start_date = new DateTime($lesson->insert_timestamp);
        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->add(new DateInterval('PT' . $lesson->thinkingMinutes . 'M'));
        $t .= "<p>".Yii::$app->formatter->asDate($start_date)." - ".Yii::$app->formatter->asDate($deadline)."</p>"; 

        if($print == "my" | $print == "compare"){
            $t .= "<p>".Yii::$app->_L->get('teacher_poll_results_countMyStudents').": ";
            $t .= $numStudents["mine"];
            $t .= "</p>";
        }
        if($print == "all" | $print == "compare"){
            $t .= "<p style='margin-top: 0.5em; margin-bottom:1em;'>".Yii::$app->_L->get('gen_students').": ";
            $t .= $numStudents["all"];
            $t .= "</p>";
        }

        //$t .= "<hr />";

        foreach($lesson->taskTypes as $type_name => $task_type){

            if(!isset($taskTypes_used[$type_name])){continue;}
            if($task_type["type"] !== "scale"){continue;}
            
            $t .= "<div class='task_explanation' style='padding: 0em;'>\n";
            $t .= '<div class="task_type_title">';
            $t .= Yii::$app->_L->get('gen_type').' "'.Yii::$app->_L->get('scale_'.$type_name.'-title').'"';
            $t .= "</div>\n";
            
            foreach($task_type["values"] as $key => $val){
                $t .= "<div>";
                $t .= "<span>".$task_type["symbols"][$val]."&nbsp;&nbsp;</span>";
                $t .= Yii::$app->_L->get('scale_'.$type_name.'-'.$key);
                $t .= " :: ".Yii::$app->_L->get('gen_value')."=".$val;
                $t .= "</div>\n";
            }
            $t .= "</div>\n";
        }
        
        //$t .= "<hr />";

            //$t .= '<div style="background: red; width:80%;">x</div>';
        
        foreach($taskAnswers as $task){
            
            $this_task_type = $lesson->taskTypes[$task["type"]];
            
            if($this_task_type["type"] == "info"){continue;}
            
            /** table to avoid page breaks within one task */
            $t .= "\n<div style='page-break-inside: avoid;'>\n";


            if($this_task_type["type"]=="scale" & $task["countNumericAnswers"] > 0){

                $my_q = "";
                if($task["my_countNumericAnswers"]>0){
                    $my_q = round($task["my_sumAnswers"]/$task["my_countNumericAnswers"], 1);
                }
                if(strpos((STRING)$my_q, ".")===false){$my_q .= ".0";}

                $q = "";
                if($task["countNumericAnswers"]>0){
                    $q = round($task["sumAnswers"]/$task["countNumericAnswers"], 1);
                }
                if(strpos((STRING)$q, ".")===false){$q .= ".0";}
                
                
                $quota = $q;
                $my_quota = $my_q;
                
                
                $t .= '<div class="task_text">';
                $t .= $task["task_text"];
                $t .= "</div>";
               

                /** horizontal bars according to the % of the answers  */

                $prefix_arr = array();
                if($print == "my"){$prefix_arr[] = "my_";}
                if($print == "all"){$prefix_arr[] = "";}
                if($print == "compare"){$prefix_arr[] = "my_"; $prefix_arr[] = "";}
                
                
                $temp = "";
                $count_prefix = 0;
                $first_symbol = "";
                $last_symbol = "";
                
                foreach($prefix_arr as $prefix){               
                
                    $count_type_keys = 0;
                    foreach($this_task_type["values"] as $key => $task_type_val){
                        $count_type_keys++;
                        if(!is_numeric($task_type_val)){continue;}
                        
                            $q = 0;
                            $val = 0;
                            if(isset($task[$prefix."distribution"][$task_type_val])){
                                $val = $task[$prefix."distribution"][$task_type_val];
                                $q = (100*round($val/$task[$prefix.'countNumericAnswers'], 2));
                            }
                            
                            $temp .= "<div style='margin-bottom: 0px; padding-right: 0.3em; height: 20px; overflow: hidden;";
                            
                            $width = $q;
                            $limit_transparent = 3;
                            $limit_symbols = 6;
                            $limit_symbols_wide = 12;
                            $this_symbol = $this_task_type["symbols"][$key];
                            if($first_symbol == ""){$first_symbol = $this_symbol;}
                            $last_symbol = $this_symbol;
                            $q_str = $val;
                            $span_width = "";
                            
                                if($q > $limit_transparent){
                                    $temp .= " background-color: ".$lesson->taskTypes[$task["type"]]["background-colors-print"][$key].";";
                                    $temp .= " text-align: right;";
                                }else{
                                    $width = 20;
                                }
                            
                            $temp .= " width:".$width."%;'>";
                            
                                if($q >= $limit_symbols_wide){
                                    $span_width = "padding-right: 0.1em; ";
                                }
                            
                            $temp .= "<span style=''>".$q_str."</span>";
                            
                            if($q >= $limit_symbols | $q <= $limit_transparent){
                                $temp .= "&nbsp;<span style='".$span_width."text-align: center;'>".$this_symbol."</span>\n";
                            }
    
                            
                            $temp .= "</div>\n";
                            
                            if($key == $this_task_type["gap_after"]){
                                $temp .= "<div style='width:100%;height:1px;border-top: 0px dotted black;";
                                $temp .= "margin-top:6px;";
                                $temp .= "margin-bottom:6px;";
                                $temp .= "'></div>";
                            }
                            
                    }
                    if($count_prefix < count($prefix_arr)-1){
                                $temp .= "<div style='width:100%;height:1px;border-top: 1px solid black;";
                                $temp .= "margin-top:12px;";
                                $temp .= "margin-bottom:12px;";
                                $temp .= "'></div>";
                    }
                    $count_prefix++;
                    
                }
                
                /** one line with the question type, the number of answers and the distribution */ 

                $temp .= "<div class='distribution'>";
                $temp .= Yii::$app->_L->get('gen_type');
                $temp .= "&nbsp;'".Yii::$app->_L->get('scale_'.$task["type"].'-title')."'&nbsp;&nbsp;";
                
                //." (".$task["my_countNumericAnswers"]
                $my_distribution = "(".$first_symbol.")&nbsp;".implode("%&nbsp;", $task["val_arr_my_percent"]);
                $my_distribution .= "%&nbsp;(".$last_symbol.")&nbsp;&nbsp;&#216;&nbsp;".$my_quota;
                
                $distribution = "(".$first_symbol.")&nbsp;".implode("%&nbsp;", $task["val_arr_all_percent"]);
                $distribution .= "%&nbsp;(".$last_symbol.")&nbsp;&nbsp;&#216;&nbsp;".$quota;

                if($print == "my"){$temp .= $my_distribution;}
                if($print == "all"){$temp .= $distribution;}
                if($print == "compare"){
                    $temp .= "(".Yii::$app->_L->get('teacher_poll_results_tab_my_results').")&nbsp;".$my_distribution;
                    $temp .= "<br />";
                    $temp .= "(".Yii::$app->_L->get('teacher_poll_results_tab_all_results').")&nbsp;".$distribution;
                }
                $temp .= "</div>\n";
                
                


                
                $t .= $temp;

            }elseif($print == "my"){
                /** Text-Antworten */
                $t .= "<p class='text_answers'>\n";
                $t .= '<span class="task_text">'.$task["task_text"].'</span>';
                if(count($task['my_textAnswers'])>0){
                    $t .= "<ul>";
                    foreach($task['my_textAnswers'] as $text){
                        $t .= "<li>".$text."</li>";
                    }
                    $t .= "</ul>";
                }else{
                    $t .= "<p>---</p>";
                }
                $t .= "</p>";
            }
        
        $t .= "</div>"; /** avoid page breaks */    
        
        }

        
        echo $t;

?>

  </body>
</html>
