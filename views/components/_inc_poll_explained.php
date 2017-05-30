
<!-- start buttons for polls in poll_or_lesson.php -->
        <style>
        .cm-info-well{
        }
        .cm-info-well h4{
            margin-top: 0.2em; margin-bottom: 0.2em;
        }
        .cm-info-well p{
            margin-left: 1em;
            font-size: larger;
        }
        .activationkey{
            font-weight: bold;
            color: darkorange;
        }
        .studentkey{
            font-weight: bold;
            color: green;
        }
        .resultkey{
        }
        ul {
            list-style: none;
            padding: 0px;
        }
        
        ul li:before
        {
            content: '-';
            margin: 0 1em;    /* any design */
        }
        h4{
            padding: 0.3em;
            background-color:rgb(217,237,247);
        }
        </style>
        
        <p style="margin-top:2em; cursor: pointer; color: darkblue;" onclick="$('#process-info').toggle();">>> So ist der Ablauf</p>
        <div id="process-info" style="display: none; padding: 0.3em; border: 0px solid darkgrey; margin-top:1em;">
           
            <div class='w1ell w1ell-lg cm-info-well'>
                <h4>Das Team</h4>
                <ul>
                    <li>formuliert Fragen oder kopiert aus einem Katalog</li>
                    <li>erstellt einen <span class="activationkey">Aktivierungscode</span></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4>Die einzelne Lehrer/in</h4>
                <ul>
                    <li>gibt den <span class="activationkey">Aktivierungscode</span> ein</li>
                    <li>erhält einen eigenen <span class="studentkey">Schüler/innen-Zugangscode</span></li>
                    <li>erhält einen eigenen <span class="resultkey">Ergebnis-Code</span></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4>Die Schüler/innen</h4>
                <ul>
                    <li>geben den <span class="studentkey">Schüler/innen-Zugangscode</span> ein</li>
                    <li>beantworten die Fragen</p>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4>Die einzelne Lehrer/in</h4>
                <ul>
                    <li>gibt ihren <span class="resultkey">Ergebnis-Code</span> ein</li>
                    <li>erhält exklusiv ihre eigenen Ergebnisse</li>
                    <li>erhält die Team-Durchschnittswerte zum Vergleich</li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4>Das Team</h4>
                <ul>
                    <li>diskutiert die Team-Durchschnittswerte</li>
                    <li>hat keinen Zugriff auf die Einzelergebnisse, und auch sonst niemand</li>
                </ul>
            </div>
        </div>    
                
                
                
                
<?php // echo Yii::$app->_L->get('gen_student'); ?>
