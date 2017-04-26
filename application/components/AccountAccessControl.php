<?php

namespace app\components;

class AccountAccessControl extends \yii\filters\AccessControl {
    public function beforeAction($action) {
        if ($this->owner->user->isGuest && $action->id != 'registration') {
            $this->denyAccess($this->owner->user);
            return false;
        }

        return true;
    }

    public function denyAccess($user)
    {
        $user->loginRequired();
    }
}