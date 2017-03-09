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
            <div class="col-sm-3 left_cab">
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
                        <a href="/account/invited-users/">
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

            <div class="col-sm-7">
                <div class="row">
                    <div class="col-sm-6 m">
                        <div class="timer">
                            <img src="/images/clock.png" alt="">
							<?php if ($time) { ?>
								<div class="timeLeft" id="countdown">00:00:00</div>
								<?php $this->registerJs("
									$('#countdown').countdown('" . $time . "', function(event) {
										$(this).html(event.strftime('%D дней %H:%M:%S'));
									});
								"); ?>
							<?php } else { ?>
								<div class="timeLeft">00:00:00</div>
                            <?php } ?>
                        </div>
                        <div class="status">
                            <a href="#"><img src="/images/bril.png" alt=""></a>
                            <div class="payStatus"><a href="/account/order">оплатить статус</a></div>
                        </div>
                        <h1><span class="vip">VIP</span>-партнёры</h1>
                        <div class="vip">

                        </div>
                    </div>


                    <div class="col-sm-6 m">
                        <div class="reward">

                            <div class="timeLeft" id="rew"><?= $account->payment->earned ?> $</div>
                            <a href="#"><img src="/images/reward.png" alt=""></a>
                        </div>
                        <div class="referals">
                            <div class="countReferals">приглашенные</div>
                            <a href="#"><img src="/images/referals.png" alt=""></a>
                        </div>
                        <div>
                            <h1>новые партнеры</h1>
                            <div style="margin-top: 20px;">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>Логин</th>
                                        <th>Уровень</th>
                                        <th>Дата</th>
                                    </tr>
                                    </tbody>

                                    <tbody class="new_style">
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
                        </div>


                    </div>
                </div>

             
            </div>

            <div class="col-sm-2 right_cab">
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