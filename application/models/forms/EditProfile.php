<?php

namespace app\models\forms;

use app\models\User;
use yii\base\Exception;

class EditProfile extends \yii\base\Model {
    public $photo;
    public $firstname;
    public $lastname;
    public $phone;
    public $skype;
    public $pmId;

    private $_user;

    public function rules() {
        return [
            [['pmId'], 'required'],
            [['pmId', 'firstname', 'lastname'], 'string', 'max' => 25],
            [['pmId'], 'match', 'pattern' => '/^[Uu]\d+$/'],
            [['phone'], 'string', 'min' => 5, 'max' => 25],
            [['skype'], 'string', 'min' => 3, 'max' => 25],

            [['photo'], 'image',
                'mimeTypes' => 'image/png, image/jpeg, image/jpg',
                'maxSize' => '204800',
                'maxFiles' => 1
            ],
        ];
    }

    public function setUser($user) {
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->phone = $user->phone;
        $this->skype = $user->skype;
        $this->pmId = $user->payment->pmId;

        $this->_user = $user;
    }

    public function run() {
        if ($this->validate()) {
            $this->_user->firstname = $this->firstname;
            $this->_user->lastname = $this->lastname;
            $this->_user->phone = $this->phone;
            $this->_user->skype = $this->skype;

            if(!$this->_user->save()) {
                throw new Exception('Can not save user');
            };

            if ($this->photo) {
                $this->_user->createPhoto($this->photo);
            }

            $this->_user->payment->pmId = ucfirst($this->pmId);
            if (!$this->_user->payment->save()) {
                throw new Exception('Can not save user payment');
            }

            return true;
        }

        return false;
    }
}