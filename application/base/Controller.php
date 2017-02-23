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
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            $this->prepareNavigation();

            return true;
        }

        return false;
    }
}