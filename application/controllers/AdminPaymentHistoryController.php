<?php

namespace app\controllers;

use app\models\forms\PaymentFilter;
use app\models\Invoice;
use app\models\PaymentHistory;
use app\models\Transaction;
use app\models\UserPayment;
use yii\base\Exception;

class AdminPaymentHistoryController extends \app\base\AdminController {
    public function processPayout($id) {
        $invoice = Invoice::findOne($id);

        if ($invoice) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $invoice->user->applyInvoice($invoice);

                $now = new \yii\db\Expression('NOW()');

                $transactionBatch = [];
                $historyBatch = [];
                $accrual = 0;
                $invoiceLevel = $invoice->user->position->level;

                $historyBatch[] = [
                    'userId' => $invoice->userId,
                    'invoiceId' => $invoice->id,
                    'type' => 'order',
                    'status' => 'payed',
                    'amount' => $invoice->amount,
                    'created' => $now,
                    'updated' => $now
                ];

                if ($invoice->user->invite->parentId != 1 && $invoice->user->invite->parentUser->status->isActive) {
                    $transactionBatch[] = [
                        'invoiceId' => $invoice->id,
                        'receiverId' => $invoice->user->invite->parentUser->id,
                        'amount' => $invoice->amount * 0.05,
                        'status' => 'created',
                        'created' => $now,
                        'updated' => $now
                    ];

                    $historyBatch[] = [
                        'userId' => $invoice->user->invite->parentUser->id,
                        'invoiceId' => $invoice->id,
                        'type' => 'invite',
                        'status' => 'waiting',
                        'amount' => $invoice->amount * 0.05,
                        'created' => $now,
                        'updated' => $now
                    ];

                    $accrual += $invoice->amount * 0.05;
                }

                if ($parents = $invoice->user->position->getParents()->with(['user.payment', 'user.status'])->all()) {
                    $pay = 0;

                    switch ($invoice->userStatus) {
                        case 'RUBY' : $pay = 0.4; break;
                        case 'EMERALD' : $pay = 0.7; break;
                        case 'SAPPHIRE' : $pay = 1.2; break;
                        case 'DIAMOND' : $pay = 3; break;
                    }

                    foreach ($parents as $parent) {
                        if ($parent->userId == 1) {
                            break;
                        }

                        if (!$parent->user->status->isActive) {
                            $invoiceLevel--;
                            continue;
                        }

                        $maxLevel = 0;
                        switch ($parent->user->status->status) {
                            case 'RUBY' : $maxLevel = 5; break;
                            case 'EMERALD' : $maxLevel = 10; break;
                            case 'SAPPHIRE' : $maxLevel = 15; break;
                            case 'DIAMOND' : $maxLevel = 21; break;
                        }

                        if (($invoiceLevel - $parent->level) > $maxLevel ) {
                            $invoiceLevel--;
                            continue;
                        }

                        if ($invoice->user->invite->parentUser->id == $parent->userId) {
                            $transactionBatch[0]['amount'] += $pay;
                            $historyBatch[1]['amount'] += $pay;
                            $historyBatch[1]['type'] = 'tree_invite';
                        } else {
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
                        }

                        $accrual += $pay;
                    }
                }

                \Yii::$app->db->createCommand()->batchInsert(
                    'transaction',
                    ['invoiceId', 'receiverId', 'amount', 'status', 'created', 'updated'],
                    $transactionBatch
                )->execute();

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
    }

    public function actionIndex() {
        if (\Yii::$app->request->isPost) {
            if ($invoiceId = \Yii::$app->request->post('payment')) {
                $this->processPayout($invoiceId);
            }
        }

        $filter = new PaymentFilter();
        $filter->load(\Yii::$app->request->get());

        return $this->render('list', [
            'filter' => $filter,
            'provider' => $filter->provider
        ]);
    }

    public function actionView($id) {
        if ($invoice = Invoice::findOne($id)) {
            $provider = new \yii\data\ActiveDataProvider([
                'pagination' => false,
                'query' => Transaction::find()
                    ->where(['invoiceId' => $id])
                    ->joinWith('receiver.user.position')
                    ->orderBy(['position.level' => SORT_DESC])
                    ->with('receiver.user.position')
            ]);

            $this->view->params['history'] = PaymentHistory::find()
                ->where(['invoiceId' => $id])
                ->andWhere(['!=', 'type', 'order'])
                ->indexBy('userId')
                ->all();

            return $this->render('one', [
                'invoice' => $invoice,
                'provider' => $provider,
            ]);
        }

        return $this->redirect(['index']);
    }
}