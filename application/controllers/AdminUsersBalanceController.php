<?php

namespace app\controllers;

use app\models\User;
use yii\data\ActiveDataProvider;

class AdminUsersBalanceController extends \app\base\AdminController {
    public function actionIndex() {
        $provider = new ActiveDataProvider([
            'query' => User::find()->with(['status', 'payment', 'invoices.transactions'])->joinWith('status', true, 'INNER JOIN'),
            'sort' => [
                'defaultOrder' => ['created' => SORT_DESC]
            ]
        ]);

        return $this->render('list', [
            'provider' => $provider
        ]);
    }

    public function actionView($id) {
        return $this->render('one', [
            'user' => User::findOne($id)
        ]);
    }
}