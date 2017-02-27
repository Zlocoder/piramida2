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

    public function actionInvite($inviteId) {
        if (!$this->user->isGuest) {
            return $this->goHome();
        }

        if (!\Yii::$app->session->has('inviteId')) {
            \Yii::$app->session->set('inviteId', $inviteId);
            \Yii::$app->session->set('inviteDate', date('Y-m-d H:i:s'));
        }

        return $this->goRegistration();
    }
}
