<?php

/*
 * @var $this yii\web\View
 * @var $registrationForm app\models\forms\RegistrationForm
*/

use kartik\widgets\ActiveForm;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Forgot password');
?>

<div class="row">
    <div class="col-lg-4 col-lg-offset-4" style="background-color: rgba(255, 255, 255, 0.65); margin-top: 20px;">
        <h1 class="text-center">Восстановление пароля</h1>

        <?php $form = ActiveForm::begin() ?>

        <?= $form->field($model, 'email') ?>

        <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php if (\Yii::$app->session->hasFlash('isPost')) { ?>
    <script>console.log('post request')</script>
    <?php \Yii::$app->session->removeFlash('isPost') ?>
<?php } ?>

<?php if (\Yii::$app->session->hasFlash('error')) { ?>
    <script>console.log('Error: <?= \Yii::$app->session->getFlash('error') ?>')</script>
    <?php \Yii::$app->session->removeFlash('error') ?>
<?php } ?>



