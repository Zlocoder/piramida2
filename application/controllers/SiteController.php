<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\User;
use app\models\forms\ForgotPassword;

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

    public function actionTermsAndConditions() {
        return $this->render('/terms-and-conditions');
    }

    public function actionTestMail() {
        $from = 'george.lemish@gmail.com';
        $to = 'george.lemish@gmail.com';

        \Yii::$app->mailer->compose('test')
            ->setFrom($from)
            ->setTo($to)
            ->setSubject('test')
            ->send();
    }


    public function actionForgotPassword() {
        $forgotModel = new ForgotPassword();

        if (\Yii::$app->request->isPost) {
            $forgotModel->load(\Yii::$app->request->post());

            if (strpos($forgotModel->login, '@')) {
                $forgotModel->email = $forgotModel->login;
                $forgotModel->login = null;
            }

            if ($newPassword = $forgotModel->newPassword) {
                \Yii::$app->mailer->compose('new-password', [
                    'newPassword' => $newPassword,
                ])->setFrom(\Yii::$app->params['mailFrom'])
                    ->setTo($forgotModel->user->email)
                    ->setSubject('Recover your password')
                    ->send();

                \Yii::$app->session->setFlash('message', 'Your new password send to email');
                return $this->goHome();
            };
        }

        return $this->render('/forgot-password', [
            'model' => $forgotModel
        ]);
    }
}
