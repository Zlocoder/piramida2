<?php

use yii\db\Migration;

use yii\base\Exception;

class m170224_122131_create_tree extends Migration
{
    public function createPosition() {
        $this->createTable('position', [
            'id' => $this->integer()->unsigned()->notNull(),
            'userId' => $this->integer()->unsigned()->notNull()->unique(),
            'appended' => $this->integer(1),
            'level' => $this->integer()->notNull(),
            'total' => $this->integer()->notNull()
        ]);

        $this->addPrimaryKey(null, 'position', 'id');

        $this->db->createCommand()->insert('position', [
            'id' => 1,
            'userId' => 1,
            'appended' => 0,
            'level' => 1,
            'total' => 0
        ])->execute();
    }

    public function createPositionCounts() {
        $this->createTable('position_counts', [
            'id' => $this->integer()->unsigned()->notNull(),
            'level' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull()
        ]);

        $this->createIndex('id_level', 'position_counts', ['id', 'level'], true);
        $this->addForeignKey('FK_positionCountsId', 'position_counts', 'id', 'position', 'id');
    }

    public function up()
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->createPosition();
            $this->createPositionCounts();
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function down()
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->dropTable('position_counts');
            $this->dropTable('position');
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
