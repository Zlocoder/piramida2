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

    <div class="col-lg-4 col-lg-offset-4" style="background-color: rgba(255, 255, 255, 0.8); padding-top: 20px; padding-bottom: 20px;">
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

        <?php if ($registrationForm->inviteId && $registrationForm->parentInvite->userId == $registrationForm->inviteId) { ?>
            <div class="form-group">
                <label class="control-label">Спонсор</label>
                <p class="form-control-static"><?= $registrationForm->parentInvite->user->login ?></p>
            </div>
        <?php } ?>

        <?= $form->field($registrationForm, 'firstname', ['inputOptions' => ['autofocus' => true]]) ?>

        <?= $form->field($registrationForm, 'lastname') ?>

        <?= $form->field($registrationForm, 'login') ?>

        <?= $form->field($registrationForm, 'email') ?>

        <?= $form->field($registrationForm, 'country')->dropDownList(require Yii::getAlias('@app/base/countries.php'), [
            'prompt' => 'Choose country...'
        ]) ?>

        <?= $form->field($registrationForm, 'phone') ?>

        <?= $form->field($registrationForm, 'skype') ?>

        <?= $form->field($registrationForm, 'pmId') ?>

        <?= $form->field($registrationForm, 'password')->passwordInput() ?>

        <?= $form->field($registrationForm, 'confirm')->passwordInput() ?>

        <div class="form-group text-center">
                <button type="submit" class="btn btn-primary"><?= Yii::t('app', 'Register') ?></button>
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



