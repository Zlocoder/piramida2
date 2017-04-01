<?php

namespace app\models\forms;

use app\models\User;
use app\models\Invite;
use app\models\UserActivation;
use app\models\UserPayment;
use app\models\UserStatus;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\Url;

class RegistrationForm extends \yii\base\Model {
    public $firstname;
    public $lastname;
    public $login;
    public $email;
    public $password;
    public $confirm;
    public $photo;
    public $pmId;
    public $phone;
    public $skype;
    public $country;
    public $agree;

    public $inviteDate;

    private $_parentInviteId;
    private $_parentInvite;
    
    private $_user;
    private $_activation;
    private $_userInvite;
    private $_userPosition;

    public function rules() {
        return [
            [['login', 'email', 'password', 'confirm', 'inviteDate', 'pmId', 'country', 'agree'], 'required'],

            [['firstname', 'lastname', 'login', 'password', 'skype'], 'string', 'min' => 3, 'max' => 25],
            [['login', 'skype', 'password'], 'match', 'pattern' => '/^[^ ]{3,25}$/'],
            [['phone'], 'string', 'min' => 5, 'max' => 25],
            [['pmId'], 'string', 'max' => 25],
            [['pmId'], 'match', 'pattern' => '/^[Uu]\d+$/'],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['inviteDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['agree'], 'boolean'],
            [['agree'], 'compare', 'compareValue' => true, 'message' => 'You must argee.'],

            [['confirm'], 'compare', 'compareAttribute' => 'password'],

            [['photo'], 'image',
                'mimeTypes' => 'image/png, image/jpeg, image/jpg',
                'maxSize' => '204800',
                'maxFiles' => 1
            ],

            [['login', 'email'], 'unique', 'targetClass' => User::className()]
        ];
    }

    public function attributeLabels() {
        return [
            'pmId' => 'Prefect Money',
        ];
    }

    public function setInviteId($inviteId) {
        $this->_parentInviteId = $inviteId;
        $this->_parentInvite = null;
    }

    public function getInviteId() {
        return $this->_parentInviteId;
    }

    public function getPhoneDigits() {
        return preg_replace('/[^\d]/', '', $this->phone);
    }

    public function run() {
        if ($this->validate()) {
            $transaction = \Yii::$app->db->beginTransaction();

            try {
                $this->createUser();
                $this->createActivation();
                $this->createPayment();
                $this->createInvite();
                $this->createStatus();
                //$this->createPosition();

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
            'email' => $this->email,
            'country' => $this->country,
            'phone' => $this->phone,
            'phoneDigits' => $this->getPhoneDigits(),
            'skype' => $this->skype,
            'active' => 0
        ]);

        if (!$user->save()) {
            throw new Exception('Can not save user.');
        }

        if ($this->photo) {
            $user->createPhoto($this->photo);
        }

        $this->_user = $user;
    }

    public function createActivation() {
        $activation = new UserActivation([
            'userId' => $this->user->id,
            'code' => UserActivation::generateCode()
        ]);

        if (!$activation->save()) {
            throw new Exception('Can not save user activation');
        }

        $this->_activation = $activation;
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

        $this->_userInvite = $invite;
    }

    private function createPosition() {
        $this->_userPosition = $this->_parentInvite->user->position->append($this->_user->id);
    }

    private function createPayment() {
        $payment = new UserPayment([
            'userId' => $this->_user->id,
            'pmId' => strtoupper($this->pmId),
            'payed' => 0,
            'earned' => 0
        ]);

        if (!$payment->save()) {
            throw new Exception('Can not save payment');
        }
    }

    private function createStatus() {
        $status = new UserStatus([
            'userId' => $this->_user->id,
            'status' => '',
            'active' => new \yii\db\Expression('NOW()')
        ]);

        if (!$status->save()) {
            throw new Exception('Can not save UserStatus');
        }
    }

    public function getUser() {
        return $this->_user;
    }

    public function getActivation() {
        return $this->_activation;
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