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
                if ($user = User::findOne($userId)) {
                    if ($user->status) {
                        if ($user->status->isActive) {
                            $user->status->active = new \yii\db\Expression('DATE_SUB(NOW(), INTERVAL 1 HOUR)');
                        } else {
                            $user->status->active = new \yii\db\Expression('DATE_ADD(NOW(), INTERVAL 1 MONTH)');
                        }
                    } else {
                        $status = new UserStatus([
                            'userId' => $userId,
                            'status' => UserStatus::STATUS_RUBY,
                            'active' => new \yii\db\Expression('NOW()')
                        ]);

                        $status->save();

                        $user->invite->parent->user->position->append($user->id);
                        $user->invite->parent->count += 1;
                        $user->invite->parent->save();
                    }
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