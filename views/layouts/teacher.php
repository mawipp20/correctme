<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    
    $this_lesson_type = Yii::$app->params["cmPollOrLesson"];
        
    NavBar::begin([
        'brandLabel' => Yii::$app->_L->get('top_nav_site_home'),
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            //['label' => Yii::$app->_L->get('top_nav_teacher_new'), 'url' => ['/site/teacher_poll_or_lesson']],
            ['label' => Yii::$app->_L->get('top_nav_teacher_new'), 'url' => ['/site/index']],
            ['label' => Yii::$app->_L->get('top_nav_teacher_about')
                , 'url' => ['/site/about_'.Yii::$app->params["cmPollOrLesson"]]],
            ['label' => Yii::$app->_L->get('top_nav_teacher_running')
                , 'url' => ['site/index_teacher']],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; correctme.de <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
