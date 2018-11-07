<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\assets\AppAsset;
AppAsset::register($this);

$this->title = 'Finished';
$this->params['breadcrumbs'][] = $this->title;

?>




<style>
#my_results{
    width: 100%;
}

#my_results td.num{
    border-top: 1em solid white;
    padding: 0.4em;
    padding-top:0em;
    vertical-align: top;
}
#screen-wrap #my_results td.num{
    width: 1%;
    background: rgb(200,200,200);
}

#my_results td.task{
    border-top: 1em solid white;
    padding: 0 0.4em;
    font-style: italic;
}
#my_results td.answer{
    padding-left: 2em;
    padding-top: 0.4em;
}


</style>


    <input type="hidden" id="print" name="print" value="">

<div class="site-about" id="<?php if($print){echo "print-wrap";}else{echo "screen-wrap";} ?>">

    
            <?php
            if(!$print){
                echo  "<p style='text-align:right;'>".Html::button('<i class="fa fa-download" aria-hidden="true"></i> pdf'
                , [
                'class' => 'btn btn-primary screen-only',
                'id'=>'pdf-me',
                'onclick' => '$("#print").val("pdf");window.open("commit_single?print=pdf", "_blank");'
                ])."</p>";
            }
            ?>
    </p>


        <?php //echo Yii::$app->_L->get('stundent_think_finished_student_message'); ?>

    <h4><?php
        echo "<table style='width:100%'><tr><td>".$student["name"]."</td>";
        echo "<td style='text-align:right'>".date("d.m.Y")."</td>";
        echo "</tr></table>";
        ?>
    </h4>
    <?php if($lesson["description"]!=""){
        echo "<h3 style=''>".$lesson["description"]."</h3>";} 
    ?>


    <table id="my_results">
    <tr>
    <?php
        for($i=0;$i < count($tasks_answers); $i++){
            echo "<tr>";
            echo "<td class='num' rowspan='2'>".$tasks_answers[$i]["num"]."</td>";
            echo "<td class='task'>".$tasks_answers[$i]["task_text"]."</td>";
            echo "</tr><tr>";
            //echo "<td class='task'>&nbsp;</td>";
            echo "<td class='answer'>".$tasks_answers[$i]["answer"]["answer_text"]."</td>";
            echo "</tr>";
        }    
    ?>
    </tr>
    </table>

</div>
