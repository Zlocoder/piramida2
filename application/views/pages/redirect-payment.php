<?php $this->beginPage() ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<body>
<?php $this->beginBody() ?>

<?= \yiidreamteam\perfectmoney\RedirectForm::widget([
    'api' => Yii::$app->perfectMoney,
    'invoiceId' => $invoiceId,
    'amount' => $amount,
    'description' => $description,
    'message' => ''
]); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


