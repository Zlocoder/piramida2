<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'My cabinet');

$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];
?>

<div class="row">
    <div class="col-lg-3 cabinet-aside">
        <div class="block person">
            <div class="block-head">Personal info</div>

            <div class="block-body">
                <p><?= $account->firstname . ' ' . $account->lastname ?></p>
                <p><img src="<?= $account->getPhotoUrl([200, 200]) ?>" /></p>

                <p>
                    <b>RefLink:</b>
                    <a href="<?= $ref = \yii\helpers\Url::to(['site/invite', 'inviteId' => $account->id]) ?>"><?= $ref ?></a>
                </p>
                <p><b>Login:</b> <?= $account->login ?></p>
                <p><b>Email:</b> <?= $account->email ?></p>
                <p><b>Invited by:</b> <?= $account->invite->parentUser->login ?></p>
                <p><b>Invited count:</b> <?= $account->invite->count ?></p>
                <p><b>Earned:</b> <?= $account->payment->earned ?></p>
                <?php if ($account->status) { ?>
                    <p><b>Status:</b> <?= $account->status->status ?></p>
                    <p><b>Time:</b> <span id="countdown"></span></p>

                    <?php $this->registerJs("
                        $('#countdown').countdown('" . $time . "', function(event) {
                            $(this).html(event.strftime('%D days %H:%M:%S'));
                        });
                    "); ?>
                <?php } else { ?>
                    <p><b>Status:</b> RUBY</p>
                    <p><b>Time:</b> <span id="countdown">00:00:00</span></p>
                <?php } ?>
                <p><b><a href="<?= \yii\helpers\Url::to(['account/order']) ?>">Pay for status</a></b></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <h4>Invited users</h4>

        <table class="table">
            <tbody>
                <tr>
                    <th>User</th>
                    <th>Level</th>
                    <th>Date</th>
                </tr>
            </tbody>

            <tbody>
                <?php foreach ($childs as $child) { ?>
                    <tr>
                        <td><?= $child['login'] ?></td>
                        <td><?= $child['level']?></td>
                        <td><?= $child['created'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="col-lg-3 cabinet-aside">
        <div class="block tree-table">
            <div class="block-head">Users in tree</div>

            <div class="block-body">
                <table class="table">
                    <tbody>
                        <?php for ($level = 1; $level < 22; $level++) { ?>
                            <tr>
                                <td class="level-name"><?= $level ?> Level</td>
                                <td class="level-max"><?= $level * 2 ?></td>
                                <td class="level-count"><?= $counts[$level] ?: 0 ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>