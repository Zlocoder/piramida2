<?php

namespace app\controllers;

use app\models\Invoice;

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
}