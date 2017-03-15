<?php

namespace app\models\forms;

use app\models\User;

class EditProfile extends \yii\base\Model {
    public $phone;
    public $skype;
    public $pmId;

    private $_user;

    public function rules() {
        return [
            [['pmId'], 'required'],
            [['pmId'], 'string', 'max' => 25],
            [['pmId'], 'match', 'pattern' => '/^[Uu]\d+$/'],
            [['phone'], 'string', 'min' => 5, 'max' => 25],
            [['skype'], 'string', 'min' => 3, 'max' => 25],

            [['phoneDigits'], 'unique', 'targetClass' => User::className(), 'filter' => ['!=', 'id', $this->_user->id]],
        ];
    }

    public function setUser($user) {
        $this->phone = $user->phone;
        $this->skype = $user->skype;

        $this->_user = $user;
    }

    public function getPhoneDigits() {
        return preg_replace('/[^\d]/', '', $this->phone);
    }

    public function run() {
        if ($this->validate()) {
            $this->_user->phone = $this->phone;
            $this->_user->skype = $this->skype;

            return $this->_user->save();
        }

        return false;
    }
}