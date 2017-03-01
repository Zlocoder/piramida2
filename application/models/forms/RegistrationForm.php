<?php

namespace app\models\forms;

use app\models\User;
use app\models\Invite;
use yii\base\Exception;

class RegistrationForm extends \yii\base\Model {
    public $firstname;
    public $lastname;
    public $login;
    public $email;
    public $password;
    public $confirm;
    public $photo;

    public $inviteDate;

    private $_parentInviteId;
    private $_parentInvite;
    
    private $_user;
    private $_userInvite;
    private $_userPosition;

    public function rules() {
        return [
            [['login', 'email', 'password', 'confirm', 'inviteDate'], 'required'],

            [['firstname', 'lastname', 'login', 'password'], 'string', 'min' => 3, 'max' => 25],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['inviteDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],

            [['confirm'], 'compare', 'compareAttribute' => 'password'],

            [['photo'], 'image',
                'mimeTypes' => 'image/png, image/jpeg, image/jpg',
                'maxSize' => '204800',
                'maxFiles' => 1
            ],

            [['login', 'email'], 'unique', 'targetClass' => User::className()]
        ];
    }

    public function setInviteId($inviteId) {
        $this->_parentInviteId = $inviteId;
        $this->_parentInvite = null;
    }

    public function getInviteId() {
        return $this->_parentInviteId;
    }

    public function run() {
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $this->createUser();
                $this->createInvite();
                $this->createPosition();

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();

                throw $e;
            }

            return true;
        }

        return false;
    }

    private function createUser() {
        $user = new User([
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'login' => $this->login,
            'password' => \Yii::$app->security->generatePasswordHash($this->password),
            'email' => $this->email
        ]);

        if (!$user->save()) {
            throw new Exception('Can not save user.');
        }

        if ($this->photo) {
            $user->createPhoto($this->photo);
        }

        $this->_user = $user;
    }

    private function createInvite() {
        $invite = new Invite([
            'userId' => $this->_user->id,
            'parentId' => $this->parentInvite->userId,
            'count' => 0,
            'inviteDate' => $this->inviteDate
        ]);

        if (!$invite->save()) {
            throw new Exception('Can not create Invite');
        }

        $this->parentInvite->count += 1;
        if (!$this->parentInvite->save()) {
            throw new Exception('Can not update parent Invite');
        }

        $this->_userInvite = $invite;
    }

    private function createPosition() {
        $this->_userPosition = $this->_parentInvite->user->position->append($this->_user->id);
    }

    public function getUser() {
        return $this->_user;
    }

    public function getUserInvite() {
        return $this->_userInvite;
    }

    public function getUserPosition() {
        return $this->_userPosition;
    }

    public function getParentInvite() {
        return $this->_parentInvite = $this->_parentInvite ?: Invite::find()
                ->where(['userId' => $this->_parentInviteId])
                ->orWhere(['userId' => 1])
                ->orderBy(['userId' => SORT_DESC])
                ->one();
    }

    public function getIsParentAdmin() {
        return $this->parentInvite->userId == 1;
    }
}