<?= \yiidreamteam\perfectmoney\RedirectForm::widget([
    'api' => Yii::$app->perfectMoney,
    'invoiceId' => $invoiceId,
    'amount' => $amount,
    'description' => $description
]); ?>