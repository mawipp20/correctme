<?php exit; ?>


SELECT lesson.startKey, teacher.name, teacher.resultkey, teacher.studentkey, count(student.id) as students FROM teacher inner JOIN lesson on lesson.startKey = teacher.startKey inner join student on student.teacher_id = teacher.id WHERE lesson.title like "%2017%" group by teacher.id

------- sofort ---------------

- keine Teamergebnisse, wenn zu wenige Teilnehmer vorhanden sind
- Hintergrundfarben zu den Optionswerten ins Model 
- show explanations for result bars:
    .distribution_one_value => visibility
    .Fragentypen mit options
    .Fragentyp bei jeder Frage einblenden
    ."(Anzahl Antworten)" neben Fragetext

    

        

------- mittelfristig ---------------

- Ergebnisbalken mit Hintergrundbild ++ + - --
- Lehrerinnen können die Umfrage ohne Werte testen
- Results: drucken, exportieren, Eingabe abschließen
    Drucken:
        https://tcpdf.org/
        http://ourcodeworld.com/articles/read/226/top-5-best-open-source-pdf-generation-libraries-for-php     
    
- Checkbox zum einverstanden-sein mit den Nutzungsbedingungen und dem 

------- später ---------------

Fragen-Sortieren: Button "In einem großen Textfeld bearbeiten und sortieren."
Text-Export aus poll_exact heraus

