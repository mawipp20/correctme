
<!-- start buttons for polls in poll_or_lesson.php -->


    <div id="poll_div" style="">
        <h3 style="margin-top: 0.5em;">
            <?= Yii::$app->_L->get('poll_or_lesson_headline1_poll');
            ?>
        </h3>
        <p class="headline2">
            <?= Yii::$app->_L->get('poll_or_lesson_headline2_poll'); ?>
        </p>
            <?php
                foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
                    echo '<div class="alert alert-danger">' . $message . "</div>\n";
                }
            ?>
            
            
            <div class='well well-lg well-correctme-as-button' style="margin-bottom: 1.2em;"
                onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/student/student_join_poll'; ?>"'><?= Yii::$app->_L->get('gen_student') ?>
            </div>
    
            <div class='well well-lg well-correctme-as-button'
                onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/site/lesson_exact?lesson_type=poll&show_teacher_join'; ?>"'><?= Yii::$app->_L->get('gen_teacher') ?>
            </div>
    </div>
