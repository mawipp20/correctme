
<!-- start buttons for polls in poll_or_lesson.php -->


    <div id="poll_div" style="">
        <h3 style="margin-top: 0.5em; margin-bottom: 1em;">
        <?= Yii::$app->_L->get('poll_or_lesson_headline_poll'); ?>
        </h3>

            <?php
                foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
                    echo '<div class="alert alert-danger">' . $message . "</div>\n";
                }
            ?>
            
            <div class='well well-lg well-poll-or-lesson' style="margin-bottom: 1.2em;"
                onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/student/student_join_poll'; ?>"'><?= Yii::$app->_L->get('gen_student') ?>
            </div>
    
            <div class='well well-lg well-poll-or-lesson'
                onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/site/lesson_exact?lesson_type=poll&show_teacher_join'; ?>"'><?= Yii::$app->_L->get('gen_teacher') ?>
            </div>
    </div>