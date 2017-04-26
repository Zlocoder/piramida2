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
            <h4 class="text-center">История начислений</h4>

            <table class="table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Операция</th>
                        <th>Сумма</th>
                        <th>Партнер</th>
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
                            <td><?= $item->invoice->user->login ?></td>
                            <td><?= $item->status ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?= $this->render('chunks/right-side', [
            'counts' => $counts
        ]) ?>
    </main>
</div>