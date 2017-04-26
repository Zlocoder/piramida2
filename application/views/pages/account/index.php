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

        <div class="col-sm-7">
            <?php if (!$account->active) { ?>
                <div class="alert alert-warning  alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$(this).parent().remove();"><span aria-hidden="true">&times;</span></button>
                    <strong>Проверте свою почту, вам оправлено письмо с сылкой активации.</strong><br/>
                    <a style="color: blue;" href="<?= \yii\helpers\Url::to(['account/resend-activation']) ?>">Отправить письмо повторно.</a>
                </div>
            <?php } ?>

            <div class="row">
                <div class="col-sm-6 m">
                    <div class="timer">
                        <img src="/images/clock.png" alt="">
<!--                        <div class="timeLeft" id="countdown">00:00:00</div>-->
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
<!--                        <div class="payStatus"><a href="/account/order">оплатить статус</a></div>-->
<!--                    </div>-->
                        <div class="payStatus"><a href="#">оплатить статус</a></div>
                    </div>
                </div>


                <div class="col-sm-6 m">
                    <div class="reward">

                        <div class="timeLeft" id="rew"><?= $account->payment->earned ?> $</div>
                        <a href="#"><img src="/images/reward.png" alt=""></a>
                    </div>
                    <div class="referals">
<!--                        <div class="countReferals">--><?//= $total_count ?><!-- участников</div>-->
                        <div class="countReferals">приглашенные</div>
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

        <?= $this->render('chunks/right-side', [
            'counts' => $counts
        ]) ?>
    </main>
</div>
