<?php

namespace app\models;

class Transaction extends \app\base\ActiveRecord {
    // ActiveRecord
    public static function tableName() {
        return 'transaction';
    }

    public function rules() {
        return [
            [['invoiceId', 'receiverId', 'amount', 'status'], 'required'],

            [['invoiceId', 'receiverId'], 'integer'],
            [['amount'], 'double'],
            [['status'], 'string', 'max' => 25],

            [['invoiceId'], 'exist', 'targetClass' => Invoice::className(), 'targetAttribute' => 'id'],
            [['receiverId'], 'exist', 'targetClass' => UserPayment::className(), 'targetAttribute' => 'userId'],
        ];
    }

    // Invoice relation
    public function getInvoice() {
        return $this->hasOne(Invoice::className(), ['id' => 'invoiceId']);
    }

    // Receiver relation
    public function getReceiver() {
        return $this->hasOne(UserPayment::className(), ['userId' => 'receiverId']);
    }

    // Receiver PaymentHistory relation
    public function getHistory() {
        return $this->hasOne(PaymentHistory::className(), ['userId' => 'receiverId', 'invoiceId' => 'invoiceId']);
    }
}