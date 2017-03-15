<?php

namespace app\models;

class UserActivation extends \app\base\ActiveRecord {
    public $timestamp = false;

    public static function tableName() {
        return 'user_activation';
    }

    public function rules()
    {
        return [
            [['userId', 'code'], 'required'],

            [['userId', 'code'], 'unique'],

            [['userId'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id']
        ];
    }

    public static function generateCode() {
        do {
            $code = \Yii::$app->security->generateRandomString();
        } while (self::findOne(['code' => $code]));

        return $code;
    }
}