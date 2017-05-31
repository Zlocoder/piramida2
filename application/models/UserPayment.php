<?php

namespace app\models;

class UserPayment extends \app\base\ActiveRecord {
    const AMOUNT_TEST = 0.01;
    const AMOUNT_RUBY = 12.5;
    const AMOUNT_EMERALD = 0;
    const AMOUNT_SAPPHIRE = 0;
    const AMOUNT_DIAMOND = 100;

    const ACCRUAL_INVITE = 20;
    const ACCRUAL_TREE = 3;

    // ActiveRecord
    public $timestamp = false;

    public static function tableName() {
        return 'user_payment';
    }

    public function rules() {
        return [
            [['userId', 'pmId'], 'required'],

            [['userId'], 'integer'],
            [['pmId'], 'string', 'max' => 25],
            [['pmId'], 'match', 'pattern' => '/^U\d+$/'],
            [['payed', 'earned'], 'double'],

            [['userId'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id']
        ];
    }

    // User relation
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    // PaymentHistory relation
    public function getHistory() {
        return $this->hasMany(PaymentHistory::className(), ['userId' => 'userId'])
            ->orderBy(['created' => SORT_DESC])->where(['!=', 'type', 'order']);
    }
}