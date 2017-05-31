<?php

namespace app\controllers;

use app\models\Invite;
use app\models\Position;
use app\models\User;
use app\models\UserStatus;
use yii\base\Exception;
use yii\bootstrap\Html;
use yii\db\Query;

class SystemController extends \app\base\AdminController {
    public $layout = 'admin';

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

                    $user = User::findOne($userId);
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

    public function checkPosition($positions, &$check, $current) {
        if (isset($positions[$current])) {
            $check[$current] = [
                'level' => strlen(decbin($current)),
                'appended' => 0,
                'total' => 0,
                'status' => UserStatus::STATUS_RUBY,
                'counts' => []
            ];

            if (isset($positions[$current << 1])) {
                $check[$current]['appended']++;
                $this->checkPosition($positions, $check, $current << 1);

                if (isset($positions[($current << 1) + 1])) {
                    $check[$current]['appended']++;
                    $this->checkPosition($positions, $check, ($current << 1) + 1);

                    switch(true) {
                        case isset($check[$current]['counts'][3]) && $check[$current]['counts'][3] == 8 :
                            $check[$current]['status'] = UserStatus::STATUS_DIAMOND;
                            break;
                        case isset($check[$current]['counts'][2]) && $check[$current]['counts'][2] == 4 :
                            $check[$current]['status'] = UserStatus::STATUS_SAPPHIRE;
                            break;
                        case isset($check[$current]['counts'][1]) && $check[$current]['counts'][1] == 2 :
                            $check[$current]['status'] = UserStatus::STATUS_EMERALD;
                            break;
                    }
                }
            }

            for ($level = $check[$current]['level'] - 1; $level > 0; $level--) {
                $parent = $current >> ($check[$current]['level'] - $level);
                $check[$parent]['total']++;
                $check[$parent]['counts'][$check[$current]['level'] - $level]++;
            }
        }
    }

    public function actionFixTree() {
        $positions = (new Query())->from('position_old')->indexBy('id')->all();
        $payment = (new Query())->from('user_payment')->indexBy('userId')->all();

        $filtered = [];
        foreach ($positions as $id => $position) {
            if ($payment[$position['userId']]['payed'] == 0 && !isset($positions[$id << 1])) {
                continue;
            }

            $filtered[$id] = $position;
        }

        $positions = $filtered;

        $check = [];
        $this->checkPosition($positions, $check, 1);

        $counts = [];
        $diamondActive = new \yii\db\Expression('DATE_ADD(NOW(), INTERVAL 1 MONTH)');
        $now = new \yii\db\Expression('NOW()');

        $statuses = [];
        foreach ($check as $id => $checked) {
            $positions[$id]['level'] = $checked['level'];
            $positions[$id]['appended'] = $checked['appended'];
            $positions[$id]['total'] = $checked['total'];

            foreach ($checked['counts'] as $level => $count) {
                $counts[] = [$id, $level, $count];
            }

            $statuses[$positions[$id]['userId']]['userId'] = $positions[$id]['userId'];
            $statuses[$positions[$id]['userId']]['status'] = $checked['status'];
            if ($checked['status'] == UserStatus::STATUS_DIAMOND) {
                $statuses[$positions[$id]['userId']]['active'] = $diamondActive;
            } else {
                $statuses[$positions[$id]['userId']]['active'] = $now;
            }
        }

        \Yii::$app->db->createCommand()->delete('position_counts')->execute();
        \Yii::$app->db->createCommand()->delete('position')->execute();
        \Yii::$app->db->createCommand()->delete('user_status')->execute();

        \Yii::$app->db->createCommand()->batchInsert(
            'user_status',
            ['userId', 'status', 'active'],
            $statuses
        )->execute();

        \Yii::$app->db->createCommand()->batchInsert(
            'position',
            ['id', 'userId', 'appended', 'level', 'total'],
            $positions
        )->execute();

        \Yii::$app->db->createCommand()->batchInsert(
            'position_counts',
            ['id', 'level', 'count'],
            $counts
        )->execute();

        return null;
    }

    public function actionFixStatus() {
        $positions = (new Query())->from('position_old')->indexBy('userId')->all();
        $statuses = (new Query())->from('user_status')->indexBy('userId')->all();

        $filtered = [];
        foreach ($statuses as $userId => $status) {
            if (isset($positions['userId'])) {
                $filtered[$userId] = $status;
            }
        }
    }

    public function actionCheckInvites() {
        $invites = Invite::find()->with('childs')->indexBy('userId')->all();
        $positions = Position::find()->indexBy('userId')->all();
        $users = User::find()->indexBy('id')->all();

        $errors = [];
        $deleted_all = [];
        foreach ($invites as $userId => $invite) {
            $deleted = 0;

            foreach ($invite->childs as $child) {
                $childId = $child->userId;

                if ($childId != $userId) {
                    if (!isset($positions[$childId])) {
                        $errors[] = "Пользователь {$users[$userId]->login}: приглашенный {$users[$childId]->login} не находится в дереве";
                        $deleted_all[] = $childId;
                        $deleted++;
                        continue;
                    }

                    if ($positions[$childId]->level < $positions[$userId]->level) {
                        $errors[] = "Пользователь {$users[$userId]->login}: приглашенный {$users[$childId]->login} находится выше в дереве";
                        continue;
                    }

                    if (($positions[$childId]->id >> ($positions[$childId]->level - $positions[$userId]->level)) != $positions[$userId]->id) {
                        $errors[] = "Пользователь {$users[$userId]->login}: приглашенный {$users[$childId]->login} не находится в дереве пригласившего";
                        continue;
                    }
                }
            }

            if ((count($invite->childs) - $deleted) != $invite->count) {
                $invite->count = count($invite->childs) - $deleted;
                if (!$invite->save()) {
                    throw new Exception('Can not update invited count');
                };

                $errors[] = "Пользователь {$users[$userId]->login}: количество приглашенных не соответствует реальности (исправлено).";
            }
        }

        Invite::deleteAll(['userId' => $deleted_all]);

        return $this->render('check-invites', [
            'errors' => $errors,
            'checked' => count($invites)
        ]);
    }
}