<?php

namespace app\controllers;

use app\models\User;
use app\models\UserStatus;
use yii\base\Exception;
use yii\bootstrap\Html;
use yii\db\Query;

class SystemController extends \app\base\AdminController {
    public function actionAppendUser() {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $userId = (new Query())
                ->select('user.id')->from('user')
                ->leftJoin('user_new', 'user_new.id = user.id')
                ->where('user_new.id IS NULL')
                ->orderBy('user.id')
                ->limit(1)->scalar();

            $user = User::findOne($userId);

            if ($user->payment->payed >= 10) {
                $now = new \yii\db\Expression('NOW()');

                if (!$user->active) {
                    $user->active = 1;
                    if (!$user->save()) {
                        throw new Exception('Can not save User');
                    };
                }

                $user->invite->parent->count += 1;
                if (!$user->invite->parent->save()) {
                    throw new Exception('Can not save parent Invite');
                }

                $status = new UserStatus([
                    'userId' => $userId,
                    'status' => UserStatus::STATUS_RUBY,
                    'active' => $now
                ]);

                if (!$status->save()) {
                    throw new Exception('Can not save UserStatus');
                };

                $user->invite->parent->user->position->append($userId);
            }

            \Yii::$app->db->createCommand()->insert('user_new', $user->toArray())->execute();

            $transaction->commit();

            return $this->renderContent("User $userId was processed. " . Html::a('Next', ['system/append-user']));
        } catch (Exception $e) {
            $transaction->rollback();

            throw $e;
        }
    }
}