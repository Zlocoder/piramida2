<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
<!--    <title>--><?//= Html::encode($this->title) ?><!--</title>-->
    <title>Diamondrewards</title>
    <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css?family=Exo|Francois+One|Lemonada|Lobster" rel="stylesheet">
    <link href="http://diamondrewards.biz/images/alma.ico" rel="shortcut icon" type="image/x-icon" />
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">-->
</head>

<body class="<?= $this->params['section_class'] ?: 'main' ?>">
<?php $this->beginBody() ?>

<section class="<?= $this->params['section_class'] ?: 'main' ?>">
    <?php if ($this->params['section_class'] == 'cabinet') { ?>
        <header>
            <div class="fluid-container">
                <div class="col-sm-6 logo">
                    <a href="/"><img src="/images/logo.png" alt=""></a>
                </div>
                <div class="col-sm-2 col-sm-offset-2 translator">
                    <!--
                                    <select name="gt" id="">
                                        <option value="1">rus</option>
                                        <option value="2">eng</option>
                                    </select>
                    -->
                    <div id="google_translate_element"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                </div>
                <div class="col-sm-2 hi" style="text-align: left;">
                    <a href="/"><div class="lc">Личный кабинет</div></a>
                    <div>Добро пожаловать,</div>
                    <div class="who vip" style="font-size: 2vw;"><?= \Yii::$app->user->fullname ?></div>
                </div>
            </div>
        </header>
    <?php } else { ?>
        <header>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 logo">
                        <a href="/"><img src="/images/logo.png" alt=""></a>
                    </div>
                    <div class="col-sm-2 col-sm-offset-2 translator">
                        <!--
                                        <select name="gt" id="">
                                            <option value="1">rus</option>
                                            <option value="2">eng</option>
                                        </select>
                        -->
                        <div id="google_translate_element"></div>
                        <script type="text/javascript">
                            function googleTranslateElementInit() {
                                new google.translate.TranslateElement({pageLanguage: 'ru', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, multilanguagePage: true}, 'google_translate_element');
                            }
                        </script>
                        <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                    </div>
                    <div class="col-sm-2 autorization">
                        <?php if (\Yii::$app->user->isGuest) { ?>
                            <a href="<?= Url::to(['site/login']) ?>"><div class="enter">Вход</div></a>
                            <a href="<?= Url::to(['account/registration']) ?>"><div class="registr">Регистрация</div></a>
                            <div class="recover"><a href="<?= Url::to(['site/forgot-password']) ?>"><div class="enter">Забыли пароль?</div></a></div>
                        <?php } else { ?>
                            <a href="<?= Url::to(['account/index']) ?>"><div class="enter">Кабинет</div></a>
                            <a href="<?= Url::to(['site/logout']) ?>"><div class="registr">Выход</div></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="row nav">
                    <ul>
                        <?php foreach ($this->params['mainNav'] as $link) { ?>
                            <div class="col-sm-2">
                                <div class="paral"><a href="<?= $link['url'] ?>"><li><?= $link['label'] ?></li></a></div>
                            </div>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </header>
    <?php } ?>

    <div class="fluid-container" style="padding-left: 15px; padding-right: 15px;">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= $content ?>
    </div>
</section>

<!--
    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= Yii::$app->name ?> <?= date('Y') ?></p>

            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
    -->

<?php $this->endBody() ?>
<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
    (function(){ var widget_id = 'LThNjoFOeR';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->
</body>
</html>
<?php $this->endPage() ?>

