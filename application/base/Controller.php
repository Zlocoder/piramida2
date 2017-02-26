<?php

namespace app\base;

use yii\helpers\Url;

class Controller extends \yii\web\Controller {
    protected function prepareNavigation() {
        $this->view->params['mainNav'] = [
            ['label' => 'Page 1', 'url' => '#'],
            ['label' => 'Page 2', 'url' => '#'],
            ['label' => 'Page 3', 'url' => '#'],
        ];

        $this->view->params['userNav'] = [];
        if (\Yii::$app->user->isGuest) {
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'SignIn'), 'url' => Url::to(['site/login'])];
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'Registration'), 'url' => Url::to(['account/registration'])];
        } else {
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'My cabinet'), 'url' => Url::to(['account/index'])];
            $this->view->params['userNav'][] = ['label' => \Yii::t('app', 'SignOut') . ' (' . \Yii::$app->user->fullname . ')', 'url' => Url::to(['site/logout'])];
        }
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            $this->prepareNavigation();

            return true;
        }

        return false;
    }
}