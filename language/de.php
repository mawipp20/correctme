<?php

/**
 * @author Martin Wippersteg
 * @copyright 2017
 * correctme.de language file
 * 
 *  German:Deutsch
 * 
 */

function _L($phrase){
    static $_L = array(
    
        'BRAND_LABEL' => 'correctme.de'
        ,'MAIN_PAGE_TITLE' => 'correctme.de - Kontrolliertes Think-Pair-Share'
        ,'WELCOME' => 'Think-Pair-Share'
        
        ,'numTasks_label' => 'Anzahl der Aufgaben'
        ,'numTasks_placeholder' => '1-10'
        
        ,'numStudents_label' => 'Anzahl aller Schüler'
        ,'numStudents_placeholder' => '2-50'
        
        ,'numTeamsize_label' => 'Pairing-Gruppengröße'
        ,'numTeamsize_placeholder' => '2-6'
        
        ,'thinkingMinutes_label' => 'Einzelarbeitsphase'
        ,'thinkingMinutes_placeholder' => '? Minuten'
        
        ,'typeTasks_label_short' => 'Kurze Antwortfelder'
        ,'typeTasks_label_long' => 'Lange Antwortfelder'
        
        ,'startKey_label' => 'Schüler-Start-Key'
        ,'startKey_placeholder' => 'mind. 6 Zeichen'
        
        ,'lesson_btn_submit' => 'Los gehts'
        
        ,'earlyPairing_label' => 'Gruppenbildung vor Ende der Einzelarbeitszeit'
        ,'typeMixing_label' => 'Leistung ausgleichende Gruppenbildung'
        ,'namedPairing_label' => 'Anonyme Gruppen'
    );
    
     return (!array_key_exists($phrase,$_L)) ? $phrase : $_L[$phrase];
}

?>