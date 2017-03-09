<div class="col-lg-10 col-lg-offset-1" style="margin-top: 20px; padding-top: 10px; padding-bottom: 10px; background-color: rgba(255,255,255,0.6)">
    <?= \yii\grid\GridView::widget([
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
                'attribute' => 'invoiceStatus',
                'label' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}'
            ]
        ]
    ]) ?>
</div>
