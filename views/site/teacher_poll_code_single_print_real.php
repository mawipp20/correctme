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
        td{
            text-align: left;
        }
        p{
            margin-top: 0em;
            margin-bottom: 0em;
        }
  </style>
  <body>
  
    <h1 class="print_hide" style="text-align: center; margin-top: 0em;">
    <button onclick="window:print();"><?= Yii::$app->_L->get('gen_print');?></button>
    </h1>


<div style="margin-top: 0em; margin-bottom: 20px;">
    <?php
        $msg_success = Yii::$app->_L->get('teacher_join_poll_login_success_team');
        $msg_success = str_replace('#lesson_title#', '"<b>'.$lesson->title.'</b>"', $msg_success);
         
        $deadline = new DateTime($lesson->insert_timestamp);
        $deadline->modify('+' . $lesson->thinkingMinutes . ' minutes');
        $deadline_str = "<span style='white-space:nowrap;'>".Yii::$app->formatter->asDate($deadline)."</span>";
        
        $msg_deadline = Yii::$app->_L->get('teacher_join_poll_one_activation_key_success_deadline');
        $msg_deadline = str_replace('#deadline#', '<b>'.$deadline_str.'</b>', $msg_deadline);
         
        $deadline_results = $deadline;
        $deadline_results->modify('+2 week');
        $deadline_results_str = "<span style='white-space:nowrap;'>".Yii::$app->formatter->asDate($deadline_results)."</span>";
        
        $msg_deadline_results = Yii::$app->_L->get('teacher_join_poll_one_activation_key_success_deadline_results');
        $msg_deadline_results = str_replace('#deadline_results#', '<b>'.$deadline_results_str.'</b>', $msg_deadline_results);
         
        echo $msg_success."<br /><br />".$msg_deadline."<br />".$msg_deadline_results;      
    ?>
</div>

<h4 style="margin-bottom: 1em; margin-top: 2em;">
<?php echo Yii::$app->_L->get('teacher_poll_codes_activation_key'); ?>
</h4>

<h3 style="margin-bottom: 2em; margin-top: 0.5em; padding-left: 3em; padding-top: 0.1em; padding-bottom:0.1em; color: black;">
<?php  
    echo "<span style='font-size: 32pt;'>";
    echo $template_teacher->activationkey;
    echo "</span><span style='font-size: 16pt; color: black;'>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp; ".Yii::$app->_L->get('gen_valid_until')." ".$deadline_str;
    echo "</span>";
?>
</h3>

  </body>
</html>

