<?php

namespace app\models\forms;

use app\models\Invoice;

class PaymentFilter extends \yii\base\Model {
    public $pmId;
    public $not_payed;

    public function rules() {
        return [
            [['pmId'], 'string', 'max' => 25],
            [['pmId'], 'match', 'pattern' => '/^U\d+$/'],
            [['not_payed'], 'boolean']
        ];
    }

    public function getProvider() {
        $this->validate();

        $query = Invoice::find()->joinWith('user.payment')->with('user.payment');

        if ($this->pmId && !$this->hasErrors('pmId')) {
            $query->andWhere(['user_payment.pmId' => $this->pmId]);
        }

        if ($this->not_payed && !$this->hasErrors('not_payed')) {
            $query->andWhere(['invoiceStatus' => 'created']);
        } else {
            $query->andWhere(['invoiceStatus' => ['payed', 'complete']]);
        }

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created' => SORT_DESC
                ]
            ]
        ]);
    }
}