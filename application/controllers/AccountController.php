<?php

namespace app\controllers;

class AccountController extends \app\base\Controller {
    public function actionIndex() {
        return $this->render('index');
    }
}