<?php

/*
 * @var $this yii\web\View
*/

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'My cabinet');
$this->params['section_class'] = 'cabinet';
//$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];

\app\assets\TreeAsset::register($this);
?>

<div class="row">
    <main class="col-lg-12">
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
            <div class="col-sm-12">
                <?= $this->render('chunks/tree-recursion', ['tree' => $tree, 'level' => 0]) ?>
                <?php $this->registerJs("
                    $('.tree-item').click(function() {
                        document.location = $(this).data('href');
                    });
                "); ?>
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
    </main>
</div>
