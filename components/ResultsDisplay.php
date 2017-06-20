<?php
namespace app\components;

use yii\base\Component;

class ResultsDisplay extends Component{

	public function init(){
		parent::init();
	}
	
	public function get_distribution($lesson, $task, $prefix, $line_gap = 0){
	   
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

	public function get_distribution_print($lesson, $task, $prefix, $line_gap = 0){
	   
        $t = "";
        
        if(is_array($lesson->taskTypes[$task["type"]]) & $task[$prefix."countNumericAnswers"] > 0){
            
            $q = round($task[$prefix."sumAnswers"]/$task[$prefix."countNumericAnswers"], 1);
            $num_options = count($lesson->taskTypes[$task["type"]]);
            $opacity_count = 0;
            $width_sum = 0;
            $width_quota = 0;
            
            $width_max = 100 - $width_quota;
            
            $margin_color = "rgb(0,255,0)";
            if($line_gap < 0){$margin_color = "rgb(255,142,30)";}
            
            
            
            $t = "<tr class='distribution' style='width:100%; border-top:".abs($line_gap)."em solid ".$margin_color.";'>\n";
            //$t .= "<td class='quota' style='max-width:".$width_quota."px;";
            //$t .= "'>".$q."</td>\n";

                foreach($lesson->taskTypes[$task["type"]] as $task_type => $task_type_val){
                    $this_opacity = (1.1 - round($opacity_count / $num_options, 2))*255;
                    $font_color = "white";
                    if($this_opacity > 180){
                        $font_color = "black";
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
                    $t .= "<td class='one_distribution'";
                    $t .= " style='width:".$width."%; color:".$font_color.";".$border_style;
                    $t .= " background: rgb(".$this_opacity.",".$this_opacity.",".$this_opacity.");'>";
                    $t .= "<span class='one_distribution_val'>".$val."</span></td>";
                }
            $t .= "\n</tr>\n";
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