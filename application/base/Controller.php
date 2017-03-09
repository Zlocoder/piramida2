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
            ['label' => 'Главная', 'url' => '/'],
            ['label' => 'Маркетинг', 'url' => '/site/marketing/'],
            ['label' => 'Новости', 'url' => '/site/news/'],
            ['label' => 'Вопрос/ответ', 'url' => '/site/faq/'],
            ['label' => 'Контакты', 'url' => '#'],
            ['label' => 'Ролики', 'url' => '/site/video/'],
        ];

        $this->view->params['userNav'] = [];
        if ($this->user->isGuest) {
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'Вход'), 'url' => Url::to(['site/login'])];
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'Регистрация'), 'url' => Url::to(['account/registration'])];
        } else {
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