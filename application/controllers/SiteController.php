<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\Invite;
use app\models\User;

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
            $user = User::findOne(['login' => $inviteId]);
            if ($user && $user->status->isActive && $user->invite) {
                \Yii::$app->session->set('inviteId', $user->id);
                \Yii::$app->session->set('inviteDate', date('Y-m-d H:i:s'));
            }
        }

        return $this->goRegistration();
    }

    public function actionMarketing() {
        return $this->render('/marketing');
    }

    public function actionFaq() {
        return $this->render('/faq');
    }

    public function actionNews() {
        return $this->render('/news');
    }

    public function actionVideo() {
        return $this->render('/video');
    }
}
