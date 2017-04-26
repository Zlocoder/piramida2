<?php

namespace app\models\forms;

use app\models\User;

class LoginForm extends \yii\base\Model {
    public $login;
    public $password;
    public $captcha;

    private $_user;

    public function rules() {
        return [
            [['login', 'password'], 'required', 'message' => 'Введите свой {attribute}'],
            [['captcha'], 'required', 'message' => 'Введите код'],

            [['login', 'password'], 'string', 'min' => 3, 'max' => 25,
                'tooShort' => '{attribute} должен быть не менее 3 символов',
                'tooLong' => '{attribute} должен быть не более 25 символов'
            ],

            [['captcha'], 'captcha', 'message' => 'Неверный код']
        ];
    }

    public function attributeLabels() {
        return [
            'login' => 'Логин',
            'password' => 'Пароль',
            'captcha' => 'Введите код с картинки'
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