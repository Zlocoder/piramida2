<h1>Welcome , <?= $name ?>. You registered in DIAMOND REWARDS</h1>

<p>Your login: <b><?= $login ?></b></p>
<p>Your password: <b><?= $password ?></b></p>
<?php if ($sponsor) { ?>
    <p>You have been invited by: <b><?= $sponsor ?></b></p>
<?php } ?>
