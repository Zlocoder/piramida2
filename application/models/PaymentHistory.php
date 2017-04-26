<?php

namespace app\models;

class PaymentHistory extends \app\base\ActiveRecord {
    // ActiveRecord
    public static function tableName() {
        return 'payment_history';
    }

    public function rules() {
        return [
            [['userId', 'invoiceId', 'type', 'status', 'amount'], 'required'],

            [['userId', 'invoiceId'], 'integer'],
            [['type', 'status'], 'string', 'max' => 25],
            [['amount'], 'double'],

            [['userId'], 'exist', 'targetClass' => UserPayment::className()],
            [['invoiceId'], 'exist', 'targetClass' => Invoice::className(), 'targetAttribute' => 'id']
        ];
    }

    // UserPayment relation
    public function getPayment() {
        return $this->hasOne(UserPayment::className(), ['userId' => 'userId']);
    }

    // Invoice relation
    public function getInvoice() {
        return $this->hasOne(Invoice::className(), ['id' => 'invoiceId']);
    }
}