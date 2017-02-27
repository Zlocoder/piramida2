<?php

namespace app\models;

class Invite extends \app\base\ActiveRecord {
    // ActiveRecord
    public $timestamp = false;

    public static function tableName() {
        return 'invite';
    }

    public function rules() {
        return [
            [['userId', 'parentId', 'inviteDate'], 'required'],

            [['count'], 'integer'],
            [['inviteDate'], 'date', 'format' => 'php:Y-m-d H:i:s'],

            [['userId'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id'],
            [['parentId'], 'exist', 'targetAttribute' => 'userId']
        ];
    }

    // Invite relations
    public function getParent() {
        return $this->hasOne(self::className(), ['userId', 'parentId']);
    }

    public function getChilds() {
        return $this->hasMany(self::className(), ['parentId', 'userId']);
    }

    // User relations
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getParentUser() {
        return $this->hasOne(User::className(), ['id' => 'parentId']);
    }

    public function getChildUsers() {
        return $this->hasMany(User::className(), ['id', 'userId'])->via('childs');
    }
}