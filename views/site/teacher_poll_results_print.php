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
            text-align: center;
        }
        .task_text{
            font-size: larger;
            padding:0.3em;
            padding-top:1em;
            margin-bottom: 0em;
        }
        .num_answers{
            padding-left: 0.5em;
            padding-right: 0.5em;
            color: black;
        }
        .text_answers{
            padding:0.3em;
        }
        .quota{
            display: inline-block;
            background-size: 380px;
            background-image: url(<?= \Yii::$app->request->BaseUrl ?>/images/transparent_dot_100.gif);
            background-repeat: no-repeat;
        }
        .quota_value{
            font-weight: bold;
            border-bottom: 10px solid rgb(100,100,100);
            text-align: center;
        }
        .quota_fill{
            border-top: 10px solid rgb(190,190,190);
        }
        .quota_distribution{
            padding: 0px;
            padding-left: 0.3em;
            font-size: small;
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
  
    <h1 class="print_hide" style="text-align: center; margin-top: 0.5em;">
    <button onclick="window:print();"><?= Yii::$app->_L->get('gen_print');?></button>
    </h1>

<?php

        $t = "<h1>".$lesson->title."</h1>";
        $start_date = new DateTime($lesson->insert_timestamp);
        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->add(new DateInterval('PT' . $lesson->thinkingMinutes . 'M'));
        $t .= "<p>".Yii::$app->formatter->asDate($start_date)." - ".Yii::$app->formatter->asDate($deadline)."</p>"; 

        
        foreach($taskAnswers as $task){
            
            if(is_array($lesson->taskTypes[$task["type"]]) & $task["countNumericAnswers"] > 0){
            
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
                
                $t .= '<p class="task_text">'.$task["task_text"]."</p>\n";
                
                $max_width_em = 15;
                $max_q = $lesson->taskTypes[$task["type"]]["max_value"];
                
                $my_q_em = $max_width_em * round($my_q/$max_q, 1);
                $q_em = $max_width_em * round($q/$max_q, 1);

                $my_div = "<div class='quota quota_value' style='width:".$my_q_em."em'>".$my_q."</div>";
                $my_div .= "<div class='quota quota_fill' style='width:".($max_width_em - $my_q_em)."em'>&nbsp;</div>";
                
                
                $all_div = "<div class='quota quota_value' style='width:".$q_em."em'>".$q."</div>";
                $all_div .= "<div class='quota quota_fill' style='width:".($max_width_em - $q_em)."em'>&nbsp;</div>";
                               
                if($print == "my"){$t .= $my_div;}
                if($print == "all"){$t .= $all_div;}
                if($print == "compare"){$t .= $my_div." ".$all_div;}

                $num_answers = "<span class='num_answers'>".$task["my_countNumericAnswers"]." ::</span>";
                
                $val_arr_my = array();
                $val_arr_all = array();
 
                 foreach($lesson->taskTypes[$task["type"]]["values"] as $task_type => $task_type_val){
                    if(isset($task["my_distribution"][$task_type_val])){
                        
                       $val = $task["my_distribution"][$task_type_val];
                       $val_arr_my[] = $val;
                       
                       $val = $task["distribution"][$task_type_val];
                       $val_arr_all[] = $val;
                       
                    }else{
                       $val_arr_my[] = "0";
                       $val_arr_all[] = "0";
                    }
                }

                $all_div = "</div>";


                if($print == "my"){$t .= "<div class='quota quota_distribution'>".$num_answers.implode("/", $val_arr_my)."</div>";}
                if($print == "all"){$t .= "<div class='quota quota_distribution'>".$num_answers.implode("/", $val_arr_all)."</div>";}
                if($print == "compare"){$t .= "<div class='quota quota_distribution'>".$num_answers.implode("/", $val_arr_my)." :: ".implode("-", $val_arr_all)."</div>";}

    
                //$t .= "\n</div>\n";
                
            }elseif($print == "my"){
                /** Text-Antworten */
                $t .= "<p class='text_answers'>\n";
                $t .= '<span style="">'.$task["task_text"].'</span>';
                if(count($task['my_textAnswers'])>0){
                    $t .= "<ul>";
                    foreach($task['my_textAnswers'] as $text){
                        $t .= "<li>".$text."</li>";
                    }
                    $t .= "</ul>";
                }else{
                    $t .= "---";
                }
                $t .= "</p>";
            }
        }

        echo $t;

?>

  </body>
</html>
