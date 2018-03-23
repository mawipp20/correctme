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
            white-space: nowrap;
        }
        .studentkey{
            font-weight: bold;
            color: green;
            white-space: nowrap;
        }
        .resultkey{
        }
        h4{
            padding: 0.3em;
            padding-bottom: 0em;
            border-bottom:4px solid rgb(217,237,247);
        }
        p.headline2{
            font-size: 18px;
            margin-bottom: 1em;
        }
        p.sequence_info_link{
            margin-top:1.5em;
            cursor: pointer; 
            color: darkblue;
            font-size: larger;
        }
        ul {
            list-style-type: "... ";
        }
        </style>
       
        
        <div id="sequence-info" style="display: none; padding: 0.3em; border: 0px solid darkgrey; margin-top:1em;">
           
            <div class='w1ell w1ell-lg cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_team_top_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_team_top_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_team_top_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_team_top_li_3') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_teacher_top_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_top_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_top_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_top_li_3') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_students_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_2') ?></p>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_teacher_bottom_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_bottom_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_bottom_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_teacher_bottom_li_3') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_team_bottom_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_team_bottom_li_1') ?></li>
                    <li  style="background-color: rgb(217,237,247);">
                    <?= Yii::$app->_L->get('info_sequence_team_bottom_li_2') ?>
                    </li>
                    <li  style="background-color: rgb(217,237,247);">
                    <?= Yii::$app->_L->get('info_sequence_team_bottom_li_3') ?>
                    </li>
                    <li><a href="../web/site/about">
                    <?= Yii::$app->_L->get('info_sequence_team_bottom_link_privacy') ?>
                    </a></li>
                </ul>
            </div>
        </div>    
                

        <div id="sequence-single-info" style="display: none; padding: 0.3em; border: 0px solid darkgrey; margin-top:1em;">
           
            <div class='w1ell w1ell-lg cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_single_team_top_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_2') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_3') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_top_li_4') ?></li>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_students_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_students_li_2') ?></p>
                </ul>
            </div>
            <div class='cm-info-well'>
                <h4><?= Yii::$app->_L->get('info_sequence_single_team_bottom_title') ?></h4>
                <ul>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_bottom_li_1') ?></li>
                    <li><?= Yii::$app->_L->get('info_sequence_single_team_bottom_li_2') ?></li>
                    <li><a href="../web/site/about">
                    <?= Yii::$app->_L->get('info_sequence_team_bottom_link_privacy') ?>
                    </a></li>
                </ul>
            </div>
        </div>    
                
