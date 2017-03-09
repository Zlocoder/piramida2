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
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <form method="post">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />

                    <div class="form-group">
                        <label>Статус</label>
                        <select name="status" class="form-control">
                            <?php foreach ($options as $status => $amount) { ?>
                                <option value="<?= $status ?>"><?= $status ?> - <?= $amount ?> USD</option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary"><?= \Yii::t('app', 'Перейти к оплате') ?></button>
                    </div>
                </form>
            </div>
        </div>
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