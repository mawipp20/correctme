
<!-- start buttons for cooperative lessons in poll_or_lesson.php -->

    <div id="lesson_div" style="">
        <h3 style="margin-top: 3em; margin-bottom: 1em;">
        <?= Yii::$app->_L->get('poll_or_lesson_headline1_lesson'); ?>
        </h3>
            <?php
                foreach(Yii::$app->getSession()->allFlashes as $key => $message) {
                    echo '<div class="alert alert-danger">' . $message . "</div>\n";
                }
            ?>
            
            <div class='well well-lg well-correctme-as-button' style="margin-bottom: 1.2em;"
                onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/student/student_join_lesson'; ?>"'>
                <?= Yii::$app->_L->get('poll_or_lesson_student_btn_goto_lesson'); ?>
            </div>
    
            <div class='well well-lg well-correctme-as-button'
                onclick='window.location.href = "<?= Yii::$app->getUrlManager()->getBaseUrl().'/site/lesson_exact'; ?>"'><?= Yii::$app->_L->get('poll_or_lesson_teacher_btn_goto_lesson'); ?>
            </div>
    </div>
        

