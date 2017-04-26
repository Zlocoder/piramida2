<?php

/*
 * @var $this yii\web\View
 * @var $loginForm app\models\forms\LoginForm
*/

use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Sign in');
?>

<div class="row">
    <div class="col-lg-4 col-lg-offset-4" style="background-color: rgba(255, 255, 255, 0.65); padding-bottom: 15px; margin-top: 20px;">
	<h1 class="text-center">Вход</h1>

        <?php $form = ActiveForm::begin([]) ?>

            <?= $form->field($loginForm, 'login', ['inputOptions' => ['autofocus' => true]]) ?>

            <?= $form->field($loginForm, 'password')->passwordInput() ?>

            <?= $form->field($loginForm, 'captcha')->widget(Captcha::className(), [
                'options' => [
                    'class' => 'form-control',
                ],
                'template' => '<div class="row"><div class="col-sm-8">{input}</div> <div class="col-sm-4" style="margin-top: -8px;">{image}</div></div>'
            ]) ?>

        <div class="form-group">
                <button class="btn btn-primary pull-right">Войти</button>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

