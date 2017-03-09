<?php

namespace app\models;

class UserStatus extends \app\base\ActiveRecord {
    // ActiveRecord;
    public $timestamp = false;

    public static function tableName() {
        return 'user_status';
    }

    public function rules() {
        return [
            [['userId', 'status', 'active'], 'required'],

            [['userId'], 'number'],
            [['status'], 'string', 'max' => 25],

            [
                'active',
                'date',
                'format' => 'php:Y-m-d H:i:s',
                'when' => function($model) {
                    return !($model->active instanceof \yii\db\Expression) && $model->active != '0000-00-00 00:00:00';
                }
            ],

            [['userId'], 'unique'],
            [['userId'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id']
        ];
    }

    // User Relation
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    // Custom methods
    public function getIsActive() {
        return $this->active > date('Y-m-d H:i:s');
    }
}