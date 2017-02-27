<?php

/*
 * @var $this yii\web\View
 * @var $registrationForm app\models\forms\RegistrationForm
*/

use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Registration');
?>

<div class="row">
    <h1 class="text-center"><?= \Yii::t('app', 'Registration') ?></h1>

    <div class="col-lg-4 col-lg-offset-4">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data'
            ]
        ]) ?>

        <?= $form->field($registrationForm, 'photo')->widget(FileInput::className(), [
            'pluginOptions' => [
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
                'browseLabel' =>  'Select Photo'
            ],
            'options' => ['accept' => 'image/jpg, image/png, image/jpeg']
        ]) ?>

        <?= $form->field($registrationForm, 'firstname', ['inputOptions' => ['autofocus' => true]]) ?>

        <?= $form->field($registrationForm, 'lastname') ?>

        <?= $form->field($registrationForm, 'login') ?>

        <?= $form->field($registrationForm, 'email') ?>

        <?= $form->field($registrationForm, 'password')->passwordInput() ?>

        <?= $form->field($registrationForm, 'confirm')->passwordInput() ?>

        <div class="form-group">
            <button class="btn btn-primary pull-right"><?= Yii::t('app', 'Send') ?></button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

