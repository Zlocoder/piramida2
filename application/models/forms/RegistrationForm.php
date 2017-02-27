<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Exception;

class RegistrationForm extends \yii\base\Model {
    public $firstname;
    public $lastname;
    public $login;
    public $email;
    public $password;
    public $confirm;
    public $photo;

    private $_user;

    public function rules() {
        return [
            [['login', 'email', 'password', 'confirm'], 'required'],

            [['firstname', 'lastname', 'login', 'password'], 'string', 'min' => 3, 'max' => 25],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],

            [['confirm'], 'compare', 'compareAttribute' => 'password'],

            [['photo'], 'image',
                'mimeTypes' => 'image/png, image/jpeg, image/jpg',
                'maxSize' => '204800',
                'maxFiles' => 1
            ],

            [['login', 'email'], 'unique', 'targetClass' => User::className()]
        ];
    }

    private function createUser() {
        $newUser = new User([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'login' => $this->login,
            'password' => \Yii::$app->security->generatePasswordHash($this->password),
            'email' => $this->email
        ]);

        if (!$newUser->save()) {
            throw new Exception('Can not save user.');
        }

        if ($this->photo) {
            $newUser->createPhoto($this->photo);
        }

        $this->_user = $newUser;
    }

    public function run() {
        if ($this->validate()) {
            $this->createUser();

            return true;
        }

        return false;
    }

    public function getUser() {
        return $this->_user;
    }
}