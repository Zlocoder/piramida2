<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'My cabinet');
$this->params['section_class'] = 'cabinet';
//$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];
?>

<div class="row">
    <main class="col-lg-12">
        <?= $this->render('chunks/left-side', [
            'account' => $account,
            'refLink' => $refLink,
            'bestUsers' => $bestUsers,
        ]) ?>

        <div class="col-lg-7">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <h4 class="text-center">Мой профиль</h4>
                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Реф ссылка</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                <a href="<?= $refLink ?>" id="refLink2" onclick="return false;">
                                    <?= $refLink ?>
                                    <span class="hint">Скопировано</span>
                                </a>
                            </p>

                            <?php $this->registerJs("
                                $('#refLink2').click(function(e) {
                                    e.preventDefault();
                                    
                                    var _temp = $('<input>');
                                    $('body').append(_temp);
                                    _temp.val($(this).attr('href')).select();
                                    document.execCommand('copy');
                                    _temp.remove();

                                    $(this).children('.hint').css({
                                        left: e.offsetX + 5,
                                        top: e.offsetY - 30
                                    }).show();
                                    
                                    setTimeout(function() {
                                        $('#refLink2 .hint').hide();
                                    }, 2000);
                                });
                            ") ?>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Фото</label>

                        <div class="col-sm-8">
                            <?= \kartik\widgets\FileInput::widget([
                                'model' => $model,
                                'attribute' => 'photo',
                                'pluginOptions' => [
                                    'showCaption' => false,
                                    'showRemove' => false,
                                    'showUpload' => false,
                                    'browseClass' => 'btn btn-primary btn-block',
                                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
                                    'browseLabel' =>  'Выбрать фото'
                                ],
                                'options' => ['accept' => 'image/jpg, image/png, image/jpeg']
                            ]) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Имя</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="EditProfile[firstname]" value="<?= $model->firstname ?>" />
                            <?php if ($model->hasErrors('firstname')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['firstname'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Фамилия</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="EditProfile[lastname]" value="<?= $model->lastname ?>" />
                            <?php if ($model->hasErrors('lastname')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['lastname'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Логин</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?= $account->login ?></p>
						</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?= $account->email ?></p>
						</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Страна</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?= $account->country ?></p>
						</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Кошелек Perfect Money</label>
                        <div class="col-sm-8">
<!--                            <p class="form-control-static">--><?//= $account->payment->pmId ?><!--</p>-->
                            <input class="form-control" type="text" name="EditProfile[pmId]" value="<?=  $model->pmId ?>" />
                            <?php if ($model->hasErrors('pmId')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['pmId'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Телефон</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="EditProfile[phone]" value="<?= $model->phone ?>" />
							<?php if ($model->hasErrors('phone')) { ?>
								<p class="help-block help-block-error"><?= $model->errors['phone'][0] ?></p>
							<?php } ?>
						</div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Skype</label>
						<div class="col-sm-8">
							<input class="form-control" type="text" name="EditProfile[skype]" value="<?= $model->skype ?>" />
							<?php if ($model->hasErrors('skype')) { ?>
								<p class="help-block help-block-error"><?= $model->errors['skype'][0] ?></p>
							<?php } ?>
						</div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>

        		<h4 class="text-center">Изменить пароль</h4>

                <form method="post" class="form-horizontal">
                    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Старый пароль</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="ChangePassword[oldPassword]" value="" />
                            <?php if ($model->hasErrors('oldPassword')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['oldPassword'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Новый пароль</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="ChangePassword[newPassword]" value="" />
                            <?php if ($model->hasErrors('newPassword')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['newPassword'][0] ?></p>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">Подтверждение пароля</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="ChangePassword[confirm]" value="" />
                            <?php if ($model->hasErrors('confirm')) { ?>
                                <p class="help-block help-block-error"><?= $model->errors['confirm'][0] ?></p>s
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <?= $this->render('chunks/right-side', [
            'counts' => $counts
        ]) ?>
    </main>
</div>


