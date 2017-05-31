<div class="col-sm-3 left_cab">
    <div class="block person">
        <div class="block-body">
            <div style="text-align: center;margin-bottom: 2vw;"><img src="<?= $account->getPhotoUrl([100, 100]) ?>" /></div>

            <p><b>Логин:</b> <?= $account->login ?></p>
            <?php if ($account->status && $account->status->isActive) { ?>
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

    <?php if ($account->status && $account->status->isActive) { ?>
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
    <?php } ?>

    <a href="/site/logout"><button>Выход</button></a>

    <div class="best_users">
        <div class="act">Акция</div>
        <table class="table">
            <thead>
            <tr>
                <th>Логин</th>
                <th>Пригласил</th>
                <th>Структура</th>
            </tr>
            </thead>

            <tbody class="new_style">
            <?php foreach($bestUsers as $user) { ?>
                <tr>
                    <td><?= $user->login ?></td>
                    <td><?= $user->invite->count ?></td>
                    <td><?= $user->position->total ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>