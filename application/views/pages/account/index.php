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
        <div class="col-sm-3 left_cab">
            <div class="block person">
                <div class="block-body">
                    <p><img src="<?= $account->getPhotoUrl([100, 100]) ?>" /></p>

                    <p><b>Логин:</b> <?= $account->login ?></p>
                    <?php if ($account->status->isActive) { ?>
                        <p><b>Статус:</b> <?= $account->status->status ?></p>
                        <p>
                            <b>Реф ссылка:</b>
                            <a href="<?= $refLink ?>" id="refLink" onclick="return false;">
                                Копировать
                                <span class="hint">Скопировано</span>
                            </a>

                            <?php $this->registerJs("
                                $('#refLink').click(function(e) {
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
                                        $('#refLink .hint').hide();
                                    }, 2000);
                                });
                            ") ?>
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
                    <a href="/account/tree/">
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
            <a href="/site/logout"><button>Выход</button></a>
        </div>

        <div class="col-sm-7">
            <?php if (!$account->active) { ?>
                <div class="alert alert-warning  alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$(this).parent().remove();"><span aria-hidden="true">&times;</span></button>
                    <strong>Проверте свою почту, вам оправлено письмо с сылкой активации.</strong>
                </div>
            <?php } ?>

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
                </div>


                <div class="col-sm-6 m">
                    <div class="reward">

                        <div class="timeLeft" id="rew"><?= $account->payment->earned ?> $</div>
                        <a href="#"><img src="/images/reward.png" alt=""></a>
                    </div>
                    <div class="referals">
                        <div class="countReferals"><?= $total_count ?> участников</div>
                        <a href="#"><img src="/images/referals.png" alt=""></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6 m">
                    <h1><span class="vip">VIP</span>-партнёры</h1>
                    <div style="margin-top: 20px;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">Логин</th>
                                    <th style="text-align: center;">Статус</th>
                                    <th style="text-align: center;">Пригласил</th>
                                </tr>
                            </thead>

                            <tbody class="new_style">
                                <?php foreach ($top_users as $user) { ?>
                                    <tr>
                                        <td><?= $user->login ?></td>
                                        <td><?= $user->status->isActive ? $user->status->status : null ?></td>
                                        <td><?= $user->invite->count ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-sm-6 m">
                    <div>
                        <h1>новые партнеры</h1>
                        <div style="margin-top: 20px;">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th style="text-align: center;">Логин</th>
                                    <th style="text-align: center;">Страна</th>

                                </tr>
                                </tbody>

                                <tbody class="new_style">
                                    <?php foreach ($last_users as $user) { ?>
                                        <tr>
                                            <td><?= $user->login ?></td>
                                            <td><?= $countries[$user->country] ?></td>

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
            <table class="table">
                <tbody class="new_style">

<!--                    <th style="text-align: center;">Логин</th>-->
<!--                    <th style="text-align: center;">Страна</th>-->




                    <?php $max = 2; ?>
                    <?php for ($level = 1; $level < 22; $level++) { ?>
                        <tr>
                            <td><?= $level ?> -</td>
                            <td><?= $max ?> -</td>
                            <td><?= $counts[$level] ?: 0 ?></td>
                            <?php $max *= 2 ?>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </main>
</div>
