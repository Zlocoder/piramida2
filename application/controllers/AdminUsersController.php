<?php

namespace app\controllers;

use app\models\forms\UsersFilter;

class AdminUsersController extends \app\base\Controller {
    public function actionIndex() {
        $filter  = new UsersFilter();
        $filter->load(\Yii::$app->request->get());

        return $this->render('index', [
            'filter' => $filter,
            'provider' => $filter->provider
        ]);
    }
}