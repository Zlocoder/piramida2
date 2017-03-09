<?php

namespace app\models;

use yii\base\Exception;
use yii\db\Query;

class Position extends \app\base\ActiveRecord {
    // ActiveRecord
    public $timestamp = false;

    public static function tableName() {
        return 'position';
    }

    public function rules() {
        return [
            [['id', 'userId', 'level'], 'required'],

            [['id', 'userId', 'level', 'total'], 'integer'],
            [['appended'], 'integer', 'min' => 0, 'max' => 2],

            [['id', 'userId'], 'unique'],
            [['userId'], 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id'],
        ];
    }

    // Position relations
    public function getParent() {
        return $this->find()->where(['id' => $this->id >> 1]);
    }

    public function getParents() {
        $position = $this->id;

        $parents = [];
        while (($position >> 1) > 0) {
            $position = $position >> 1;
            $parents[] = $position;
        }

        return $this->find()->where(['id' => $parents])->orderBy(['level' => SORT_DESC]);
    }

    public function getLeft() {
        return $this->find()->where(['id' => $this->id << 1]);
    }

    public function getRight() {
        return $this->find()->where(['id' => ($this->id << 1) + 1]);
    }

    // User relations
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public function getChildUsers() {
        return (new Query())
            ->from("position as p")
            ->leftJoin('user as u', 'u.id = p.userId')
            ->select(['u.*', 'p.id as position', new \yii\db\Expression("p.level - {$this->level} as level")])
            ->orderBy("p.id")
            ->where("p.level > {$this->level} AND (p.id >> (p.level - $this->level) = {$this->id})");
    }

    // Custom methods
    public function getNextEmptyPosition() {
        return self::find()
            ->where([
                'and',
                "id >> (level - {$this->level}) = {$this->id}",
                ['<', 'appended', 2]
            ])
            ->orderBy('level, appended, id')
            ->limit(1)
            ->one();
    }

    public function getCounts() {
        return (new Query())
            ->select('count, level')
            ->from('position_counts')
            ->where(['id' => $this->id])
            ->orderBy('level')
            ->indexBy('level')
            ->column();
    }

    public function append($userId) {
        if ($this->appended == 2) {
            return $this->nextEmptyPosition->append($userId);
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $child = new self([
                'id' => $this->appended ? ($this->id << 1) + 1 : ($this->id << 1),
                'userId' => $userId,
                'appended' => 0,
                'level' => $this->level + 1,
                'total' => 0
            ]);

            if (!$child->save()) {
                throw new Exception('Can not save child position.');
            }

            $this->appended += 1;
            if (!$this->save()) {
                throw new Exception('Can not update position');
            };

            \Yii::$app->db->createCommand("
                UPDATE `position`
                SET `total` = `total` + 1
                WHERE `id` = {$this->id} >> ({$this->level} - `level`)
            ")->execute();

            \Yii::$app->db->createCommand("
                INSERT INTO `position_counts`
                    SELECT `id`, {$child->level} - `level`, 1
                    FROM `position`
                    WHERE `id` = {$child->id} >> ({$child->level} - `level`) AND `id` != {$child->id}
                ON DUPLICATE KEY UPDATE `count` = `count` + 1
            ")->execute();

            $transaction->commit();
            return $child;
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}