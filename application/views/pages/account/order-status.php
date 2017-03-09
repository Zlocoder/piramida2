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
                            <div class="r">Кабинет</div>
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
						<form method="post" target="_blank">
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

