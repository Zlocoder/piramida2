<?php

namespace app\components;

class User extends \yii\web\User {
    public function __get($name) {
        if ($this->identity && $this->identity->canGetProperty($name)) {
            return $this->identity->$name;
        }

        return parent::__get($name);
    }

    public function __call($method, $params = []) {
        if ($this->identity->hasMethod($method)) {
            call_user_func_array([$this->identity, $method], $params);
        }
    }
}