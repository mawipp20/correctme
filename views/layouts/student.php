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
    
    $this_brandLabel = $this->params['model']->name;
    $lesson_type = $this->params['lesson']->type;
    if($lesson_type == "poll"){
        $this_brandLabel = $this->params['lesson']->title;
    }
    
    NavBar::begin([
        'brandLabel' => $this_brandLabel,
        'brandUrl' => null,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top navbar-student',
        ],
    ]);
/**
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            ['label' => Yii::$app->_L->get('top_nav_student_minutes')
                , 'url' => null
                , 'options' => [
                    'class' => 'navbar-student'
                    ,'id' => 'top_nav_student_minutes'
                    ]
            ],
        ],
    ]);
*/
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            //['label' => Yii::$app->_L->get('top_nav_student_cancel'), 'url' => ['/student/cancel']],
            '<button class="btn btn-success navbar-btn navbar-student-btn"'
            .' onclick=\'window.location.href="'.\Yii::$app->getUrlManager()->getBaseUrl().'/student/cancel";\''
            .'>'
            .Yii::$app->_L->get('top_nav_student_finish_'.$lesson_type).'</button>',
            /**
            ['label' => 'About', 'url' => ['/site/about_'.Yii::$app->params["cmPollOrLesson"]]],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
            */
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?php
        /** 
        echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ])
        */
        ?>
        <?= $content ?>
    </div>
</div>

<!--

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; correctme.de <?= date('Y') ?></p>

        <p class="pull-right"></p>
    </div>
</footer>

-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
