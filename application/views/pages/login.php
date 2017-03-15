<?php

/*
 * @var $this yii\web\View
 * @var $loginForm app\models\forms\LoginForm
*/

use yii\bootstrap\ActiveForm;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Sign in');
?>

<div class="row">
    <div class="col-lg-4 col-lg-offset-4" style="background-color: rgba(255, 255, 255, 0.65); padding-bottom: 15px; margin-top: 20px;">
	<h1 class="text-center"><?= \Yii::t('app', 'Please enter') ?></h1>

        <?php $form = ActiveForm::begin([

        ]) ?>

            <?= $form->field($loginForm, 'login', ['inputOptions' => ['autofocus' => true]]) ?>

            <?= $form->field($loginForm, 'password')->passwordInput() ?>

            <div class="form-group">
                <button class="btn btn-primary pull-right"><?= Yii::t('app', 'Sign in') ?></button>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

