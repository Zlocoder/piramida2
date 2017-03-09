<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'My cabinet');
$this->params['section_class'] = 'cabinet';
//$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col-lg-3 left_cab">
                <div class="block person">


                    <div class="block-body">
<!--                        <p>--><?//= $account->firstname . ' ' . $account->lastname ?><!--</p>-->
                        <p><img src="<?= $account->getPhotoUrl([100, 100]) ?>" /></p>

                        <p><b>Логин:</b> <?= $account->login ?></p>
						<?php if ($account->status->isActive) { ?>
							<p><b>Статус:</b> <?= $account->status->status ?></p>
							<p>
								<b>Реф ссылка:</b>
								<a href="" onclick="return false;"><?= $refLink ?></a>
							</p>
					<?php } ?>
                    </div>
                </div>
                
                <ul>
                    <li>
                        <a href="/account/">
                            <div class="sq"><div class="sq2"></div></div>
                            <div class="r">Кибинет</div>
                        </a>
                    </li>

                    <li>
                        <a href="/account/edit-profile">
                            <div class="sq"><div class="sq2"></div></div>
                            <div class="r">Профиль</div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="sq"><div class="sq2"></div></div>
                            <div class="r">Матрица</div>
                        </a>
                    </li>
                    <li>
                        <a href="/account/invited-users">
                            <div class="sq"><div class="sq2"></div></div>
                            <div class="r">Личные</div>
                        </a>
                    </li>
                    <li>
                        <a href="/account/payment-history">
                            <div class="sq"><div class="sq2"></div></div>
                            <div class="r">История</div>
                        </a>
                    </li>
					
					<?php if (\Yii::$app->user->id == 1) { ?>
						<li>
							<a href="/admin-users/">
								<div class="sq"><div class="sq2"></div></div>
								<div class="r">Все пользователи</div>
							</a>
						</li>
						
						<li>
							<a href="/admin-payment-history/">
								<div class="sq"><div class="sq2"></div></div>
								<div class="r">Все покупки статусов</div>
							</a>
						</li>
					<?php } ?>
                </ul>
                <button><a href="/site/logout">Выход</a></button>
            </div>
			

            <div class="col-lg-7">
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

            <div class="col-lg-2 right_cab">
                <div class="levels">Уровни</div>
                <ul>
                    <?php $max = 2; ?>
                    <?php for ($level = 1; $level < 22; $level++) { ?>
                        <li><?= $level ?> - <?= $max ?> - <?= $counts[$level] ?: 0 ?></li>
                        <?php $max *= 2 ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

</main>

