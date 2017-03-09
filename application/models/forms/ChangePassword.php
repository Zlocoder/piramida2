<?php

namespace app\models\forms;

class ChangePassword extends \yii\base\Model {
    public $oldPassword;
    public $newPassword;
    public $confirm;

    private $_user;

    public function rules() {
        return [
            [['oldPassword', 'newPassword', 'confirm'], 'required'],

            [['oldPassword', 'newPassword', 'confirm'], 'string', 'min' => 3, 'max' => 25],
            [['confirm'], 'compare', 'compareAttribute' => 'newPassword'],

            [['oldPassword'], 'validateOldPassword'],
        ];
    }

    public function setUser($user) {
        $this->_user = $user;
    }

    public function validateOldPassword() {
        if (!$this->hasErrors('oldPassword')) {
            if (!\Yii::$app->security->validatePassword($this->oldPassword, $this->_user->password)) {
                $this->addError('oldPassword', 'Wrong old password');
            }
        }
    }

    public function run() {
        if ($this->validate()) {
            $this->_user->password = \Yii::$app->security->generatePasswordHash($this->newPassword);
            return $this->_user->save();
        }

        return false;
    }
}