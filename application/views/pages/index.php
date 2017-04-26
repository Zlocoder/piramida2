<?php

/* @var $this yii\web\View */

$this->title = 'Diamondrewards';
?>

<div class="col-lg-10 col-lg-offset-1" style="padding-top: 10px; padding-bottom: 10px; margin-top: 20px;">
    <?php if (\Yii::$app->session->hasFlash('message')) { ?>
        <div class="alert alert-success  alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$(this).parent().remove();"><span aria-hidden="true">&times;</span></button>
            <strong><?= \Yii::$app->session->getFlash('message') ?></strong>
        </div>
        <?php \Yii::$app->session->removeFlash('message'); ?>
    <?php } ?>
</div>

<div class="col-lg-10 col-lg-offset-1 text-center color_gold" style="padding-bottom: 10px;">
    <p>
        Чтобы достигнуть успеха,
        нужно приложить усилия
        и каждый день стремиться
        к своей цели!
    </p>
</div>
