<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class ForgotPassword extends \yii\base\Model {
    public $email;

    private $_user;

    public function rules() {
        return [
            [['email'], 'required', 'message' => 'Укажите свой Email'],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'exist', 'targetClass' => User::className()]
        ];
    }

    public function attributeLabels() {
        return [
            'email' => 'Введите свой email'
        ];
    }

    public function getNewPassword() {
        if ($this->validate()) {
            $this->_user = User::findOne(['email' => $this->email]);
            $newPassword = \Yii::$app->security->generateRandomString(8);
            $this->_user->password = \Yii::$app->security->generatePasswordHash($newPassword);
            if (!$this->_user->save()) {
                throw new Exception('Can not update user password');
            };

            return $newPassword;
        }

        return false;
    }

    public function getUser() {
        return $this->_user;
    }
}