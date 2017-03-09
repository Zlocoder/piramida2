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

                <p><b>Login:</b> <?= $account->login ?></p>
                <p><b>Email:</b> <?= $account->email ?></p>
                <p><b>Invited by:</b> <?= $account->invite->parentUser->login ?></p>
                <p><b>Invited count:</b> <?= $account->invite->count ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <h4>Status payout history</h4>

        <table class="table">
            <tbody>
            <tr>
                <th>Date</th>
                <th>Operation</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
            </tbody>

            <tbody>
                <?php foreach ($history as $item) { ?>
                    <tr>
                        <td><?= $item->created ?></td>

                        <td>
                            <?php
                            switch ($item->type) {
                                case 'order' : echo 'Оплата статуса'; break;
                                case 'invite' : echo 'Начисление от приглашений'; break;
                                case 'tree' : echo 'Начисление от дерева'; break;
                                case 'tree_invite' : echo 'Начисление от приглашенного в дереве'; break;
                            }
                            ?>
                        </td>

                        <td><?= $item->type == 'order' ? '-' : '+' ?><?= $item->amount ?></td>
                        <td><?= $item->status ?></td>
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