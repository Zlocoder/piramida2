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
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link href="https://fonts.googleapis.com/css?family=Exo|Francois+One|Lemonada|Lobster" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">-->
</head>

<body>
    <?php $this->beginBody() ?>

    <section class="<?= $this->params['section_class'] ?: 'main' ?>">
        <?php if ($this->params['section_class'] == 'cabinet') { ?>
            <header>
                <div class="fluid-container">
                        <div class="col-sm-6 logo">
                            <a href="/"><img src="/images/logo.png" alt=""></a>
                        </div>
                        <div class="col-sm-2 col-sm-offset-4 hi">
                            <a href="/account/index"><div class="lc">Личный кабинет</div></a>
                            <div>Добро пожаловать,</div>
                            <div class="who"><?= \Yii::$app->user->fullname ?></div>
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
                            <select name="gt" id="">
                                <option value="1">rus</option>
                                <option value="2">eng</option>
                            </select>
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
</body>
</html>
<?php $this->endPage() ?>
