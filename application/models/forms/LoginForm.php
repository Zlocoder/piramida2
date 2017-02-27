<?php

namespace app\models\forms;

use app\models\User;

class LoginForm extends \yii\base\Model {
    public $login;
    public $password;

    private $_user;

    public function rules() {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password'], 'string', 'min' => 3, 'max' => 25],
        ];
    }

    public function afterValidate() {
        parent::afterValidate();

        if (!$this->hasErrors()) {
            $user = User::findOne(['login' => $this->login]);

            if ($user && \Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->_user = $user;
            } else {
                $this->addError('login', \Yii::t('app', 'Wrong login or password'));
            }
        }
    }

    public function getUser() {
        return $this->_user;
    }
}