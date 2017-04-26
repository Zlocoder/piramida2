<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
require(__DIR__ . '/project/vendor/autoload.php');
require(__DIR__ . '/project/vendor/yiisoft/yii2/Yii.php');

$application = new yii\web\Application(include(__DIR__ . '/project/application/config/web.php'));

\Yii::info('proccess-transactions', 'cron');

$transactions = \app\models\Transaction::find()
    ->where(['status' => 'created'])
    ->orderBy(['created' => SORT_ASC])
    ->with(['receiver', 'history'])
    ->limit(10)
    ->all();

\Yii::info('Take ' . count($transactions) . ' to commit', 'cron');

foreach ($transactions as $transaction) {
    $response = \Yii::$app->perfectMoney->transfer($transaction->receiver->pmId, $transaction->amount);

    if (!isset($response['ERROR'])) {
        $transaction->status = 'payed';
        $transaction->save();
        $transaction->history->status = 'payed';
        $transaction->history->save();
        $transaction->receiver->earned += $transaction->amount;
        $transaction->receiver->save();
        \Yii::info("Transaction complete: transfer {$transaction->amount} to {$transaction->receiver->userId} - {$transaction->receiver->pmId}", 'cron');
    } else {
        \Yii::info("Transaction error: transfer {$transaction->amount} to {$transaction->receiver->userId} - {$transaction->receiver->pmId}. {$response['ERROR']}", 'cron');
    }
}
