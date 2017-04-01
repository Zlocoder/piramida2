<?php

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Покупки статуса');

$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];

$content = \yii\grid\GridView::widget([
    'filterModel' => $filter,
    'dataProvider' => $provider,
    'columns' => [
        [
            'attribute' => 'created',
            'label' => 'date'
        ],
        [
            'attribute' => 'userStatus',
            'label' => 'status'
        ],
        'amount',
        [
            'label' => 'accrual',
            'value' => function($model) {
                return $model->amount - $model->accrual;
            }
        ],
        [
            'attribute' => 'user',
            'value' => 'user.login'
        ],
        [
            'label' => 'pm id',
            'value' => function($model) {
                return \yii\bootstrap\Html::a($model->user->payment->pmId, ['pmId' => $model->user->payment->pmId]);
            },
            'format' => 'html'
        ],
        [
            'attribute' => 'invoiceStatus',
            'label' => $model->invoiceStatus == 'created' ? null : 'Payout status',
            'value' => function ($model) {
                if ($model->invoiceStatus == 'created') {
                    return '<a href="' . \yii\helpers\Url::to(['admin-payment-history/index', 'id' => $model->id]) . '"
                            class="process-payout btn btn-default"
                            data-invoice="' . $model->id . '">Обработать</a>';
                }

                return $model->invoiceStatus;
            },
            'format' => 'raw'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}'
        ]
    ]
])
?>

<div class="row">
    <h1><?= \Yii::t('app', 'Покупки статуса') ?></h1>

    <?= \yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => 'Оплачено',
                'active' => $filter->not_payed ? false : true,
                'url' => \yii\helpers\Url::to(['admin-payment-history/index']),
                'content' => $filter->not_payed ? null : $content
            ],

            [
                'label' => 'Не оплачено',
                'active' => $filter->not_payed ? true : false,
                'url' => \yii\helpers\Url::to(['admin-payment-history/index', 'PaymentFilter' => ['not_payed' => true]]),
                'content' => $filter->not_payed ? $content : null
            ],
        ]
    ]) ?>
</div>

<form id="payment-form" method="post">
    <input type="hidden" name="_csrf" value="<?= \Yii::$app->request->csrfToken ?>" />
    <input type="hidden" name="payment" value="" />
    <button type="submit" style="display: none;"></button>
</form>

<?php
$this->registerJs("
        $('.process-payout').click(function(e) {
            e.preventDefault();
            $('#payment-form input[name=payment]').val($(this).data('invoice'));
            $('#payment-form button').click();
        }); 
    ");
?>