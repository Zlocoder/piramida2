<div class="col-lg-10 col-lg-offset-1" style="margin-top: 20px; padding-top: 10px; padding-bottom: 10px; background-color: rgba(255,255,255,0.6)">
    <h1>Оплата статуса от пользователя <?= $invoice->user->login ?></h1>

    <?= \yii\widgets\DetailView::widget([
        'model' => $invoice,
        'attributes' => [
            [
                'label' => 'User',
                'value' => $invoice->user->login
            ],

            [
                'label' => 'Tree level',
                'value' => $invoice->user->position->level
            ],

            [
                'label' => 'Order status',
                'value' => $invoice->userStatus
            ],

            [
                'label' => 'Pay date',
                'value' => $invoice->updated
            ],

            [
                'label' => 'Payout status',
                'value' => $invoice->invoiceStatus
            ],

            [
                'label' => 'User payed',
                'value' => $invoice->amount . '$'
            ],

            [
                'label' => 'Admin receive',
                'value' => $invoice->amount - $invoice->accrual . '$'
            ],

            [
                'label' => 'User parents receive',
                'value' => $invoice->accrual . '$'
            ],
        ]
    ]) ?>
</div>

<div class="col-lg-10 col-lg-offset-1" style="margin-top: 20px; padding-top: 10px; padding-bottom: 10px; background-color: rgba(255,255,255,0.6)">
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            [
                'label' => 'Tree user',
                'value' => 'receiver.user.login'
            ],

            [
                'label' => 'Level',
                'value' => 'receiver.user.position.level'
            ],

            [
                'label' => 'Amount',
                'value' => 'amount'
            ],

            [
                'label' => 'Type',
                'value' => function($model) {
                    switch($this->params['history'][$model->receiverId]->type) {
                        case 'tree' : return 'pay for tree';
                        case 'invite' : return 'pay for invite';
                        case 'tree_invite' : return 'pay for tree and invite';
                    }
                }
            ]
        ]
    ]) ?>
</div>