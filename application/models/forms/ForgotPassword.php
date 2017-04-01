<?php

namespace app\models\forms;

use app\models\User;
use yii\helpers\ArrayHelper;

class ForgotPassword extends \yii\base\Model {
    public $login;
    public $email;

    private $_user;

    public function rules() {
        return [
            [['login'], 'string', 'min' => 3, 'max' => 25],
            [['login'], 'exist', 'targetClass' => User::className()],

            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'exist', 'targetClass' => User::className()]
        ];
    }

    public function attributeLabels() {
        return [
            'login' => 'Login or email'
        ];
    }

    public function getNewPassword() {
        if ($this->validate()) {
            if ($this->login) {
                $this->_user = User::findOne(['login' => $this->login]);
                $newPassword = \Yii::$app->security->generateRandomString(8);
                $this->_user->password = \Yii::$app->security->generatePasswordHash($newPassword);
                if (!$this->_user->save()) {
                    throw new Exception('Can not update user password');
                };

                return $newPassword;
            }

            if ($this->email) {
                $this->_user = User::findOne(['email' => $this->email]);
                $newPassword = \Yii::$app->security->generateRandomString(8);
                $this->_user->password = \Yii::$app->security->generatePasswordHash($newPassword);
                if (!$this->_user->save()) {
                    throw new Exception('Can not update user password');
                };

                return $newPassword;
            }

            $this->addError('login', 'Please set the field');
        }

        $this->addErrors(['login' => ArrayHelper::merge($this->getErrors('login'), $this->getErrors('email'))]);

        return false;
    }

    public function getUser() {
        return $this->_user;
    }
}