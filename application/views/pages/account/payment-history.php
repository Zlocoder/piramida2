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
                <button><a href="/site/logout">Выход</a></button>
            </div>

            <div class="col-lg-7">
					<h4 class="text-center">История оплат статуса</h4>

					<table class="table">
						<thead>
						<tr>
                					<th>Дата</th>
                					<th>Операция</th>
                					<th>Сумма</th>
                					<th>Статус операции</th>
						</tr>
						</thead>

						<tbody>
					                <?php foreach ($history as $item) { ?>
                    <tr>
                        <td><?= $item->created ?></td>

                        <td>
                            <?php
                            switch ($item->type) {
                                case 'order' : echo 'Оплата статуса'; break;
                                case 'invite' : echo 'Начисление от приглашений'; break;
                                case 'tree' : echo 'Начисление от дерева'; break;
                                case 'tree_invite' : echo 'Начисление от приглашенного в дереве'; break;
                            }
                            ?>
                        </td>

                        <td><?= $item->type == 'order' ? '-' : '+' ?><?= $item->amount ?></td>
                        <td><?= $item->status ?></td>
                    </tr>
					                <?php } ?>
						</tbody>
					</table>
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
    </main>
</div>

<!--
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
-->