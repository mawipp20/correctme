<?php

/**
 * @author Martin Wippersteg
 * @copyright 2017
 * correctme.de language file
 * 
 *  German:Deutsch
 * 
 */

function Yii::$app->_L->get($phrase){
    
    $_sub_arrays = array();
    
    /** General */
    
    static $_L_general = array(
        
            'BRAND_LABEL' => 'correctme.de'
            ,'MAIN_LAYOUT_TITLE' => 'correctme.de - kooperatives Lernen mit Übersicht'
            ,'LESSON_WELCOME' => 'Kontrolliertes Kooperatives Lernen'
            
            ,'gen_btn_close_dialog' => 'schließen'
            ,'top_nav_teacher_about' => 'Info'
            ,'top_nav_teacher_new' => 'Neu'
            ,'top_nav_reacher_running' => 'Laufend'
            ,'top_nav_student_cancel' => 'beenden'
            ,'top_nav_student_finish' => 'abschließen'
            ,'error_server_connect' => 'Es konnte keine Verbindung zum Server hergestellt werden.'
            
            );
    $_sub_arrays["_L_general"] = $_L_general;       

    /** About */
    
    static $_L_about = array(
        
            'about_title' => 'Über correctme.de'
            ,'about_text' => 'Die innovative freie Plattform zum kontrollierten kooperativen Lernen im Unterricht.'
            
            );
    $_sub_arrays["_L_about"] = $_L_about;       

    /** Student join */
    
    static $_L_student_join = array(
        
            'student_join_title' => 'Als Lernende/r'
            
            ,'student_join_startKey_label' => 'Zugangsschlüssel'
            ,'student_join_startKey_placeholder' => ''

            ,'student_join_name_label' => 'Echter Vorname'
            ,'student_join_name_placeholder' => ''
            
            ,'student_join_key_required_message' => '... fragen Sie bitte nach'
            ,'student_join_name_required_message' => 'Bitte geben Sie Ihren Vornamen ein'
            ,'student_join_name_already_existing' => 'Dieser Name wird schon benutzt.'
            
            ,'student_join_btn_submit' => 'mitmachen'
            
            );
    $_sub_arrays["_L_student_join"] = $_L_student_join;       

    /** Teacher's Lesson Start Page */
    
    static $_L_lesson = array(
    
            'lesson_title' => 'correctme Lehrer'
            
            //'LESSON_WELCOME_line_2' => ''
            
            ,'lesson_nav_tab_quick' => 'sofort'
            ,'lesson_nav_tab_exact' => 'eingeben'
            ,'lesson_nav_tab_upload' => 'hochladen'

            ,'lesson_tasks_title' => 'Aufgaben oder Fragen eingeben'
            ,'lesson_tasks_first_placeholder_explain_strg_v' => 'Text eingeben oder mehrere Aufgaben kopieren und hier einfügen'
            //,'lesson_tasks_input_placeholder' => ''
            ,'lesson_tasks_type_text' => 'Aufgabe'
            ,'lesson_tasks_type_how_true' => 'Skala zutreffend'
            ,'lesson_tasks_type_how_often' => 'Skala Häufigkeit'
            ,'lesson_tasks_btn_analyse_task_text' => 'Zeilen aufteilen'
            
            
            
            ,'session_rejoin_title' => 'Laufende Session'
            
            ,'startKey_label' => 'Session'
            ,'startKey_placeholder' => 'vorhandenes Kennwort'
       
            ,'teacherKey_label' => 'Moderation'
            ,'teacherKey_placeholder' => 'vorhandenes Kennwort'
            
            ,'numTasks_label' => 'Aufgaben'
            ,'numTasks_placeholder' => 'Anzahl'
            ,'numTasks_required' => 'Anzahl der Aufgaben'

            ,'numStudents_label' => 'Alle Lernenden'
            ,'numStudents_placeholder' => 'Anzahl'
            ,'numStudents_required' => 'Anzahl aller anwesenden Schüler/innen'
            
            ,'numTeamsize_label' => 'Gruppen'
            ,'numTeamsize_placeholder' => 'Größe'
            ,'numTeamsize_required' => 'Größe der Gruppen für die Austauschphase'
            
            ,'thinkingMinutes_label' => 'Einzelarbeitszeit'
            ,'thinkingMinutes_placeholder' => 'Minuten'
            ,'thinkingMinutes_required' => 'Minuten'
            
            ,'typeTasks_label_short' => 'Kurze Antwortfelder'
            ,'typeTasks_label_long' => 'Lange Antwortfelder'
            
            ,'lesson_btn_submit' => "Einzelarbeit starten"
            ,'lesson_btn_rejoin_session' => "Laufende Sessions"
            
            ,'rejoin_session_btn_new_session' => "Neue Session"
            ,'rejoin_session_btn_submit' => "Session aufrufen"
            ,'join_session_login_error_flash' => "Kennwörter nicht erkannt"
            
            //,'earlyPairing_label' => 'Gruppenbildung vor Ende der Einzelarbeitszeit'
            //,'typeMixing_label' => 'Leistung ausgleichende Gruppenbildung'
            //,'namedPairing_label' => 'Anonyme Gruppen'
    );
    $_sub_arrays["_L_lesson"] = $_L_lesson;       

    /** Teacher's Think-Phase page */
    
    static $_L_think = array(
    
            'THINK_TITLE' => 'Einzelarbeitsphase'
            ,'THINK_WELCOME' => 'Einzelarbeitsphase'
            ,'think_btn_submit' => 'Ansicht aktualisieren'
            
            ,'think_dialog_startKey_info_header' => 'Login-Schlüssel für die Lernenden'
            ,'think_dialog_startKey_info_btn_caption' => '?'
            ,'think_dialog_startKey_info_text' => 'Eindeutiger Login-Schlüssel zur Bekanntgabe an die Lernenden.'
            
            ,'think_btn_show_teacher_retrieve_session_key' => 'Moderation-Kennwort anzeigen'
            ,'think_dialog_teacherKey_info_text' => 'Benutzen Sie dieses Kennwort, falls Ihr Browser ungewollt beendet wird und Sie diese Moderationsseite wieder aufrufen wollen.'
            
    );
    $_sub_arrays["_L_think"] = $_L_think;


    /** students's Think-Phase page */
    
    static $_L_student_think = array(
    
            'student_think_title' => 'Einzelarbeitsphase'
            ,'student_think_working_time' => 'noch etwa # Minuten'
            ,'student_think_messageHelpHeader' => 'Deine LehrerIn kann gleich sehen, dass du bei dieser Aufgabe nicht weiterkommst.'
            ,'student_think_messageHelpText' => 'Vielleicht schaust du dir inzwischen die anderen Aufgabe an?'
            //,'student_think_btn_back' => 'zurück'
            //,'student_think_btn_forward' => 'weiter'
            //,'student_think_btn_task_finished' => 'fertig'
            ,'student_think_working_overtime' => 'Die geplante Zeit ist seit # Minuten beendet.'
            
    );
    $_sub_arrays["_L_student_think"] = $_L_student_think;

    
    if(array_key_exists($phrase,$_sub_arrays)){return $_sub_arrays[$phrase];}       

    $temp = array();
    foreach($_sub_arrays as $this_sub_array){
        $temp = array_merge($temp, $this_sub_array);
    }
    $_L = $temp;
    
    return (!array_key_exists($phrase,$_L)) ? $phrase : $_L[$phrase];
}

?>