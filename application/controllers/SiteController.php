<?php

namespace app\controllers;

use app\models\forms\LoginForm;

class SiteController extends \app\base\Controller
{
    public function actionIndex()
    {
        return $this->render('/index');
    }

    public function actionLogin() {
        $loginForm = new LoginForm();

        if ($loginForm->load(\Yii::$app->request->post()) && $loginForm->validate()) {
            if (\Yii::$app->user->login($loginForm->user)) {
                return $this->goAccount();
            };

            return $this->goHome();
        }

        return $this->render('/login', [
            'loginForm' => $loginForm
        ]);
    }

    public function actionLogout() {
        \Yii::$app->user->logout();
        return $this->goHome();
    }
}
