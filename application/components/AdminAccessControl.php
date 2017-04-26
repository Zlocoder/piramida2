<?php

namespace app\components;

class AdminAccessControl extends \yii\filters\AccessControl {
    public function beforeAction($action) {
        if ($this->owner->user->isGuest || $this->owner->user->id != 1) {
            $this->denyAccess($this->owner->user);
            return false;
        }

        return true;
    }

    protected function denyAccess($user)
    {
        if (!$user->getIsGuest()) {
            $user->logout();
        }

        $user->loginRequired();
    }
}