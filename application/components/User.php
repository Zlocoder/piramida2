<?php

namespace app\components;

class User extends \yii\web\User {
    public function __get($name) {
        if ($this->identity && $this->identity->canGetProperty($name)) {
            return $this->identity->$name;
        }

        return parent::__get($name);
    }
}