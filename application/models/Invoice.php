<?php

namespace app\models;

class Invoice extends \app\base\ActiveRecord {
    // ActiveRecord
    public static function tableName() {
        return 'invoice';
    }

    public function rules() {
        return [
            [['userId', 'userStatus', 'invoiceStatus', 'amount', 'accrual'], 'required'],

            [['userId'], 'integer'],
            [['amount', 'accrual'], 'double'],
            [['userStatus', 'invoiceStatus'], 'string', 'max' => 25],

            [['userId'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id']
        ];
    }

    // User relations
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    // Custom fields
    public function getDescription() {
        return "Оплата статуса ({$this->userStatus}) пользователем ({$this->user->login})";
    }

    // Transaction relation
    public function getTransactions() {
        return $this->hasMany(Transaction::className(), ['invoiceId' => 'id'])->orderBy('id');
    }
}