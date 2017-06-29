<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\Url;

class ResultsDisplay extends Component{

	public function init(){
		parent::init();
	}
	
	public function get_distribution($lesson, $task, $prefix, $line_gap = 0){
	   
        $t = "";
        $task_type = $lesson->taskTypes[$task["type"]];
        
        if(is_array($lesson->taskTypes[$task["type"]]) & $task[$prefix."countNumericAnswers"] > 0){
            
            $q = round($task[$prefix."sumAnswers"]/$task[$prefix."countNumericAnswers"], 1);
            $num_options = count($task[$prefix."distribution"]);
            $count = 0;
            $width_sum = 0;
            $width_quota = 12;
            $slug = 2;
            
            $width_max = 100 - $width_quota - $slug;
            
            if($line_gap > 0){
                $gap_color = "white";
                //$gap_color = "rgb(255,142,30)";
                $t .= "<div style='width:".(100-$slug)."%;'>";
                $t .= "<div style='";
                //$t .= " background-image: url(".\yii\helpers\Url::base()."/images/gap.gif);";
                //$t .= " background-repeat: repeat;";
                $t .= " margin-left:10px;";
                $t .= " height:".$line_gap."em;";
                $t .= " width:".(100-$slug)."%;";
                $t .= " border-left: ".$line_gap."em solid darkgrey;";
                $t .= "'>&nbsp;</div></div>";
            }
            
            $t .= "<div class='distribution' style='width:".(100-$slug)."%; background-width:".(100-$slug)."%;";
            //$t .= " border-top:".abs($line_gap)."em solid ".$margin_color.";'>\n";
            $t .= "'>\n";
            $t .= "<div class='one_distribution quota' style='width:".$width_quota."%;";
            $t .= "'>".$q."</div>";

                foreach($lesson->taskTypes[$task["type"]]["values"] as $key => $task_type_val){
                    $val = 0;
                    if(isset($task[$prefix."distribution"][$task_type_val])){
                       $count++;
                       $val = $task[$prefix."distribution"][$task_type_val];
                       $width = floor(round($val/$task[$prefix."countNumericAnswers"], 3)*$width_max);
                       $width_sum += $width;
                       if($count == $num_options){
                            $width += ($width_max - $width_sum);
                            $width_sum += ($width_max - $width_sum);
                        }
                    }else{
                        continue;
                    }
                    $t .= "<div class='one_distribution'";
                    $t .= " style='";
                    $t .= " width:".$width."%;";
                    $t .= " background-width:".$width."%;";
                    $t .= " color:".$task_type["font-colors"][$task_type_val].";";
                    $t .= " background-color: ".$task_type["background-colors"][$task_type_val].";";
                    if(isset($task_type["background-images"][$task_type_val])){
                        $t .= " background-image: url(".\yii\helpers\Url::base()."/images/".$task_type["background-images"][$task_type_val].");";
                    }
                    $t .= "'>";
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
	
}
?>