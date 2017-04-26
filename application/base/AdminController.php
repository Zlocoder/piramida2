<?php

namespace app\base;

class AdminController extends \yii\web\Controller {
    public $layout = 'admin';

    public function getUser() {
        return \Yii::$app->user;
    }

    public function behaviors()
    {
        return [
            'access' => 'app\components\AdminAccessControl'
        ];
    }
}