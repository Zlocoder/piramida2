<?php

namespace app\models;

use app\base\ActiveRecord;
use yii\web\IdentityInterface;

class Change extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }
    
    public function rules(){
        return [
            [['firstname'], 'required'],
            ['firstname', 'string']
        ];
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

}