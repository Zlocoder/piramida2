<?php

namespace app\controllers;

use app\models\Invoice;
use app\models\Transaction;
use app\models\UserPayment;
use app\models\UserStatus;
use yiidreamteam\perfectmoney\actions\ResultAction;
use yiidreamteam\perfectmoney\events\GatewayEvent;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

class PaymentController extends \app\base\Controller {
    public $enableCsrfValidation = false;

    public function behaviors() {
        return [
            'access' => 'app\components\AccountAccessControl'
        ];
    }

    public function actions()
    {
        return [
            'result' => [
                'class' => ResultAction::className(),
                'componentName' => 'perfectMoney',
                'redirectUrl' => ['payment/return'],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        $pm = \Yii::$app->perfectMoney;
        $pm->on(GatewayEvent::EVENT_PAYMENT_REQUEST, [$this, 'handlePaymentRequest']);
        $pm->on(GatewayEvent::EVENT_PAYMENT_SUCCESS, [$this, 'handlePaymentSuccess']);

        if (class_exists('yii\debug\Module')) {
            $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
        }
    }

    public function actionIndex() {
        try {
            if (!\Yii::$app->session->hasFlash('orderStatus') ||
                !\Yii::$app->session->hasFlash('orderAmount') ||
                \Yii::$app->session->getFlash('orderUserId') != $this->user->id
            ) {
                throw new Exception('Wrong session data');
            }

            $invoice = new Invoice([
                'userId' => $this->user->id,
                'userStatus' => \Yii::$app->session->getFlash('orderStatus'),
                'invoiceStatus' => 'created',
                'amount' => \Yii::$app->session->getFlash('orderAmount'),
                'accrual' => 0
            ]);

            if (!$invoice->save()) {
                throw new Exception('Can not save invoice');
            }

            \Yii::$app->session->removeAllFlashes();

            $this->layout = false;
            return $this->render('/redirect-payment', [
                'invoiceId' => $invoice->id,
                'amount' => $invoice->amount,
                'description' => $invoice->description,
            ]);
        } catch (Exception $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->redirect(['account/order']);
        }
    }

    public function actionSuccess() {
        $invoice = Invoice::findOne(\Yii::$app->request->post('PAYMENT_ID'));

        if ($invoice) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $this->user->applyInvoice($invoice);

                $now = new \yii\db\Expression('NOW()');
                $accrual = 0;
                $historyBatch = [
                    [
                        'userId' => $this->user->id,
                        'invoiceId' => $invoice->id,
                        'type' => 'order',
                        'status' => 'payed',
                        'amount' => $invoice->amount,
                        'created' => $now,
                        'updated' => $now
                    ]
                ];

                if ($invoice->userStatus == UserStatus::STATUS_DIAMOND) {
                    $transactionBatch = [];

                    if ($this->user->invite->parentId != 1 && $this->user->invite->parentUser->status->isActive) {
                        $transactionBatch[] = [
                            'invoiceId' => $invoice->id,
                            'receiverId' => $this->user->invite->parentUser->id,
                            'amount' => UserPayment::ACCRUAL_INVITE,
                            'status' => 'created',
                            'created' => $now,
                            'updated' => $now
                        ];

                        $historyBatch[] = [
                            'userId' => $this->user->invite->parentUser->id,
                            'invoiceId' => $invoice->id,
                            'type' => 'invite',
                            'status' => 'waiting',
                            'amount' => UserPayment::ACCRUAL_INVITE,
                            'created' => $now,
                            'updated' => $now
                        ];

                        $accrual += UserPayment::ACCRUAL_INVITE;
                    }

                    if ($parents = $this->user->position->getParents()->with(['user.payment', 'user.status'])->all()) {
                        $pay = UserPayment::ACCRUAL_TREE;
                        $invoiceLevel = $this->user->position->level;

                        foreach ($parents as $parent) {
                            if ($parent->userId == 1) {
                                break;
                            }

                            if (!$parent->user->status->isActive) {
                                $invoiceLevel--; // Pass inactive
                                continue;
                            }

                            // check tree-accrual level by status
                            /*
                            $maxLevel = 0;
                            switch ($parent->user->status->status) {
                                case UserStatus::STATUS_RUBY : $maxLevel = 5; break;
                                case 'EMERALD' : $maxLevel = 10; break;
                                case 'SAPPHIRE' : $maxLevel = 15; break;
                                case 'DIAMOND' : $maxLevel = 21; break;
                            }

                            if (($invoiceLevel - $parent->level) > $maxLevel ) {
                                $invoiceLevel--;
                                continue;
                            }
                            */

                            $transactionBatch[] = [
                                'invoiceId' => $invoice->id,
                                'receiverId' => $parent->userId,
                                'amount' => $pay,
                                'status' => 'created',
                                'created' => $now,
                                'updated' => $now
                            ];

                            $historyBatch[] = [
                                'userId' => $parent->userId,
                                'invoiceId' => $invoice->id,
                                'type' => 'tree',
                                'status' => 'waiting',
                                'amount' => $pay,
                                'created' => $now,
                                'updated' => $now
                            ];

                            $accrual += $pay;
                        }
                    }

                    \Yii::$app->db->createCommand()->batchInsert(
                        'transaction',
                        ['invoiceId', 'receiverId', 'amount', 'status', 'created', 'updated'],
                        $transactionBatch
                    )->execute();
                }

                \Yii::$app->db->createCommand()->batchInsert(
                    'payment_history',
                    ['userId', 'invoiceId', 'type', 'status', 'amount', 'created', 'updated'],
                    $historyBatch
                )->execute();

                $invoice->invoiceStatus = 'payed';
                $invoice->accrual = $accrual;
                if (!$invoice->save()) {
                    throw new Exception('Can not update Invoice');
                }

                $adminPayment = UserPayment::findOne(1);
                $adminPayment->earned += $invoice->amount - $accrual;
                if (!$adminPayment->save()) {
                    throw new Exception('Can not update admin payment');
                };

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->goAccount();
    }

    public function actionFailure() {
        Invoice::deleteAll(['id' => \Yii::$app->request->post('PAYMENT_ID')]);

        return $this->goAccount();
    }

    public function actionProccessTransactions() {
        \Yii::info('payment/proccess-transactions', 'cron');

        $transactions = Transaction::find()
            ->where(['status' => 'created'])
            ->orderBy(['created' => SORT_ASC])
            ->with(['receiver', 'history'])
            ->limit(10)
            ->all();

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
    }

    public function actionReturn() {
        \Yii::info('payment/return');
        \Yii::info(\Yii::$app->request->post());

        return $this->goAccount();
    }

    public function handlePaymentRequest($event) {
        \Yii::info('payment/handlePaymentRequest');

        $event->invoice = Invoice::findOne(ArrayHelper::getValue($event->gatewayData, 'PAYMENT_ID'));
        $event->handled = true;
    }

    public function handlePaymentSuccess($event) {
        \Yii::info('payment/handlePaymentSuccess');

        $event->invoice->invoiceStatus = 'complete';
        $event->invoice->save();

        $event->handled = true;
    }
}