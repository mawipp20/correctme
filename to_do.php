<?php exit; ?>


    info-Frage als letztes: "abschließen-Button"
    lehrer_think: mein Name mit Anrede nicht bei Typ "mit Gesamtergebnis"
    

SELECT lesson.startKey, teacher.name, teacher.resultkey, teacher.studentkey, count(student.id) as students FROM teacher inner JOIN lesson on lesson.startKey = teacher.startKey inner join student on student.teacher_id = teacher.id WHERE lesson.title like "%2017%" group by teacher.id

------- sofort ---------------

- teacher_lesson.js: Möglichkeit, die Fragen in ein großes Textfeld zurückzuführen, um sie zu sortieren und wieder aufzuteilen.

    

        

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

