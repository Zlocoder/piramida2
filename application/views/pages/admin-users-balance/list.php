<?php

$this->title = Yii::$app->name . ' - ' . Yii::t('app', 'Баланс пользователей');

$this->params['breadcrumbs'][] = ['label' => 'My cabinet'];

echo \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'columns' => [
        'login',
        [
            'label' => 'status',
            'value' => 'status.status',
        ],
        [
            'label' => 'payed for status',
            'value' => function($user) {
                if ($user->payment->payed < 12.5) {
                    return $user->payment->payed . ' <span class="text-danger">(' . (12.5 - $user->payment->payed) . ')</span>';
                } else if ($user->payment->payed > 12.5) {
                    return $user->payment->payed . ' <span class="text-primary">(' . ($user->payment->payed - 12.5). ')</span>';
                } else {
                    return $user->payment->payed;
                }
            },
            'format' => 'raw'
        ],
        [
            'label' => 'payed for tree',
            'value' => function($user) {
                if ($user->id == 1) {
                    return 0;
                }

                $summ = 0;
                foreach ($user->invoices as $invoice) {
                    if ($invoice->invoiceStatus == 'payed' || $invoice->invoiceStatus == 'complete') {
                        foreach ($invoice->transactions as $transaction) {
                            $summ += $transaction->amount;
                        }
                    }
                }

                $must = 0;
                if ($user->status->status == \app\models\UserStatus::STATUS_DIAMOND) {
                    $must += \app\models\UserPayment::ACCRUAL_TREE * ($user->position->level - 1);
                    $must += \app\models\UserPayment::ACCRUAL_INVITE;
                }

                if ($summ < $must) {
                    return $summ . ' <span class="text-danger">(' . ($must - $summ) . ')</span>';
                } else if ($summ > $must) {
                    return $summ . ' <span class="text-primary">(' . ($summ - $must) . ')</span>';
                } else {
                    return $summ;
                }
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