<?php exit; ?>

How to ...

...add a question type:

    [language].ini
        
        scale_[how-often]... 
    
    model Lesson.php:   $taskTypes add the emlement: (e.g.) "plus-minus"=>array("type" => "scale", ...
                        $taskTypesOrder add the element
    
    Datenbank: ALTER TABLE `task` CHANGE `type` `type` SET('how-often','how-true','text','info','sysinfo','plus-minus') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;