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
                <?php foreach ($account->invite->childUsers as $invited) { ?>
                    <tr>
                        <td><?= $invited->login ?></td>
                        <td><?= $invited->position->level - $account->position->level ?></td>
                        <td><?= $invited->created ?></td>
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