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
                <h4>My profile</h4>

                <form method="post" class="form-horizontal">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Firstname</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?= $account->firstname ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Lastname</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?= $account->lastname ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">login</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?= $account->login ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?= $account->email ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><?= $account->country ?></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Phone</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="EditProfile[phone]" value="<?= $model->phone ?>" />
                            <?php if ($model->hasErrors('phone')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['phone'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Skype</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="EditProfile[skype]" value="<?= $model->skype ?>" />
                            <?php if ($model->hasErrors('skype')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['skype'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary"><?= \Yii::t('app', 'Save') ?></button>
                    </div>
                </form>

                <h4>Change password</h4>

                <form method="post" class="form-horizontal">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Old password</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="ChangePassword[oldPassword]" value="" />
                            <?php if ($model->hasErrors('oldPassword')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['oldPassword'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">New password</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="ChangePassword[newPassword]" value="" />
                            <?php if ($model->hasErrors('newPassword')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['newPassword'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirm</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="ChangePassword[confirm]" value="" />
                            <?php if ($model->hasErrors('confirm')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['confirm'][0] ?></p>s
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary"><?= \Yii::t('app', 'Save') ?></button>
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