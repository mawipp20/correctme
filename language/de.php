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
            ,'gen_btn_close_dialog' => 'schließen'

    /** Teacher's Lesson Start Page */
    
            ,'MAIN_LAYOUT_TITLE' => 'correctme.de - kooperatives Lernen mit Übersicht'
            ,'LESSON_WELCOME' => 'kooperatives Lernen'
            ,'LESSON_WELCOME_line_2' => 'mit Moderation'
        
            ,'startKey_label' => 'Session'
            ,'startKey_placeholder' => 'vorhandenes Kennwort'
            
            ,'teacherKey_label' => 'Moderation'
            ,'teacherKey' => 'vorhandenes Kennwort'
            
            ,'numTasks_label' => 'Anzahl der Aufgaben'
            ,'numTasks_placeholder' => '1-10'
            
            ,'numStudents_label' => 'Anzahl aller Lernenden'
            ,'numStudents_placeholder' => '2-50'
            
            ,'numTeamsize_label' => 'Pairing-Gruppengröße'
            ,'numTeamsize_placeholder' => '2-6'
            
            ,'thinkingMinutes_label' => 'Einzelarbeitsphase'
            ,'thinkingMinutes_placeholder' => '? Minuten'
            
            ,'typeTasks_label_short' => 'Kurze Antwortfelder'
            ,'typeTasks_label_long' => 'Lange Antwortfelder'
            
            ,'lesson_btn_submit' => "Einzelarbeitsphase starten"
            ,'lesson_btn_rejoin_session' => "Laufende Sessions"
            
            //,'earlyPairing_label' => 'Gruppenbildung vor Ende der Einzelarbeitszeit'
            //,'typeMixing_label' => 'Leistung ausgleichende Gruppenbildung'
            //,'namedPairing_label' => 'Anonyme Gruppen'


    /** Teacher's Think-Phase page */
    
            ,'THINK_TITLE' => 'Einzelarbeitsphase'
            ,'THINK_WELCOME' => 'Einzelarbeitsphase'
            ,'think_btn_submit' => 'Ansicht aktualisieren'
            
            ,'think_dialog_startKey_info_header' => 'Login-Schlüssel für die Lernenden'
            ,'think_dialog_startKey_info_btn_caption' => '?'
            ,'think_dialog_startKey_info_text' => 'Eindeutiger Login-Schlüssel zur Bekanntgabe an die Lernenden.'
            
            ,'think_btn_show_teacher_retrieve_session_key' => 'Moderation-Kennwort anzeigen'
            ,'think_dialog_teacherKey_info_text' => 'Benuten Sie dieses Kennwort, falls Ihr Browser ungewollt beendet wird und Sie diese Moderationsseite wieder aufrufen wollen.'
            



    );
    
     return (!array_key_exists($phrase,$_L)) ? $phrase : $_L[$phrase];
}

?>