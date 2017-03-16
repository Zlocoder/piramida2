<?php

namespace app\controllers;

use app\models\Invoice;
use app\models\PaymentHistory;
use app\models\Transaction;

class AdminPaymentHistoryController extends \app\base\AdminController {
    public function actionIndex() {
        $provider = new \yii\data\ActiveDataProvider([
           'query' => Invoice::find()
               ->where(['!=', 'invoiceStatus', 'created'])
               ->with('user'),
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_DESC
                ]
            ]
        ]);

        return $this->render('list', [
            'provider' => $provider
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