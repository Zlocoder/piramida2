<?php

namespace app\controllers;

use app\models\User;
use app\models\forms\UsersFilter;
use app\models\Position;
use app\models\UserStatus;

class AdminUsersController extends \app\base\AdminController {
    public function actionIndex() {
        if (\Yii::$app->request->isPost) {
            if ($userId = \Yii::$app->request->post('activity')) {
                if ($status = UserStatus::findOne($userId)) {
                    if ($status->isActive) {
                        $status->active = new \yii\db\Expression('DATE_SUB(NOW(), INTERVAL 1 HOUR)');
                    } else {
                        $status->active = new \yii\db\Expression('DATE_ADD(NOW(), INTERVAL 1 MONTH)');

                        if (!Position::findOne(['userId' => $userId])) {
                            $user = User::findOne($userId);
                            $user->invite->parentUser->position->append($userId);
                        }
                    }

                    $status->save();
                }
            }
        }

        $filter  = new UsersFilter();
        $filter->load(\Yii::$app->request->get());

        return $this->render('index', [
            'filter' => $filter,
            'provider' => $filter->provider
        ]);
    }
}