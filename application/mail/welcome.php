<h1>Welcome to DIAMOND REWARDS</h1>

<p>Your login: <b><?= $login ?></b></p>
<p>Your password: <b><?= $password ?></b></p>
<?php if ($sponsor) { ?>
    <p>You have been invited by: <b><?= $sponsor ?></b></p>
<?php } ?>

<p>You must activate your account. Go to <a href="<?= 'http://' . \Yii::$app->request->hostName . \yii\helpers\Url::to(['account/activation', 'code' => $code]) ?>">this link</a> please.</p>

<p><b>Invite partners and build your own tree. Earn money together.</b></p>

