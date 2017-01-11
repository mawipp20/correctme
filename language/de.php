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

    /** General */
        
            'BRAND_LABEL' => 'correctme.de'

    /** Teacher's Lesson Start Page */
    
            ,'MAIN_LAYOUT_TITLE' => 'correctme.de - kooperatives Lernen mit Übersicht'
            ,'LESSON_WELCOME' => 'kooperatives Lernen'
            ,'LESSON_WELCOME_line_2' => 'mit Übersicht'
        
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
            
            //,'startKey_label' => 'Schüler-Start-Key'
            //,'startKey_placeholder' => 'mind. 6 Zeichen'
            
            ,'lesson_btn_submit' => "Einzelarbeitsphase starten"
            
            //,'earlyPairing_label' => 'Gruppenbildung vor Ende der Einzelarbeitszeit'
            //,'typeMixing_label' => 'Leistung ausgleichende Gruppenbildung'
            //,'namedPairing_label' => 'Anonyme Gruppen'


    /** Teacher's Think-Phase page */
    
            ,"THINK_TITLE" => 'Einzelarbeitsphase'
            ,'THINK_WELCOME' => 'Einzelarbeitsphase'
            



    );
    
     return (!array_key_exists($phrase,$_L)) ? $phrase : $_L[$phrase];
}

?>