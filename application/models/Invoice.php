<?php

namespace app\models;

class Invoice extends \app\base\ActiveRecord {
    // ActiveRecord
    public static function tableName() {
        return 'invoice';
    }

    public function rules() {
        return [
            [['userId', 'userStatus', 'invoiceStatus', 'amount'], 'required'],

            [['userId'], 'integer'],
            [['amount'], 'double'],
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
        return "Оплата статуса ({$this->userStatus})";
    }
}