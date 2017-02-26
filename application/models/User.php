<?php

namespace app\models;

use app\base\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface {
    // IdentityInterface
    public static function findIdentity($id) {
        return self::findOne($id);
    }

    public function getId() {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return null;
    }

    public function getAuthKey() {
        return null;
    }

    public function validateAuthKey($authKey) {
        return false;
    }

    // ActiveRecord
    public static function tableName() {
        return 'user';
    }

    public function rules() {
        return [
            [['firstname', 'lastname', 'login', 'password', 'email'], 'required'],

            [['firstname', 'lastname', 'login'], 'string', 'min' => 3, 'max' => 25],
            [['password'], 'string', 'min' => 60, 'max' => 60],
            [['email'], 'string', 'max' => 100],

            [['email'], 'email'],

            [['login', 'email'], 'unique'],

            [['created', 'updated'], 'safe']
        ];
    }

    // Custom fields
    public function getFullname() {
        if ($this->firstname == $this->lastname) {
            return $this->firstname;
        }

        return $this->firtsname . ' ' . $this->lastname;
    }
}