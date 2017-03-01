<?php

namespace app\base;

use yii\helpers\Url;

class Controller extends \yii\web\Controller {
    public function getUser() {
        return \Yii::$app->user;
    }

    public function init() {
        if (!\Yii::$app->session->has('inviteDate')) {
            \Yii::$app->session->set('inviteDate', date('Y-m-d H:i:s'));
        }
    }

    protected function prepareNavigation() {
        $this->view->params['mainNav'] = [
            ['label' => 'Page 1', 'url' => '#'],
            ['label' => 'Page 2', 'url' => '#'],
            ['label' => 'Page 3', 'url' => '#'],
        ];

        $this->view->params['userNav'] = [];
        if ($this->user->isGuest) {
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'SignIn'), 'url' => Url::to(['site/login'])];
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'Registration'), 'url' => Url::to(['account/registration'])];
        } else {
            if ($this->user->id == 1) {
                $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'Users'), 'url' => Url::to(['admin-users/index'])];
            }

            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'My cabinet'), 'url' => Url::to(['account/index'])];
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'SignOut') . " ({$this->user->fullname})", 'url' => Url::to(['site/logout'])];
        }
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            $this->prepareNavigation();

            return true;
        }

        return false;
    }

    public function goLogin() {
        return $this->redirect($this->user->loginUrl);
    }

    public function goRegistration() {
        return $this->redirect(['account/registration']);
    }

    public function goAccount() {
        return $this->redirect(['account/index']);
    }
}