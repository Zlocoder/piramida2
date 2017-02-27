<?php

use yii\db\Migration;
use app\models\Invite;

class m170224_010529_create_invite_table extends Migration
{
    public function up()
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->createTable('invite', [
                'userId' => $this->integer()->unsigned()->notNull(),
                'parentId' => $this->integer()->unsigned()->notNull(),
                'count' => $this->integer()->notNull(),
                'inviteDate' => $this->dateTime()->notNull(),
            ]);

            $this->addPrimaryKey(null, 'invite', 'userId');
            $this->addForeignKey('FK_inviteUserId', 'invite', 'userId', 'user', 'id');
            $this->addForeignKey('FK_inviteParentId', 'invite', 'parentId', 'invite', 'userId');

            $this->db->createCommand()->insert('invite', [
                'userId' => 1,
                'parentId' => 1,
                'count' => 0,
                'inviteDate' => new \yii\db\Expression('NOW()'),
            ])->execute();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function down()
    {
        $this->dropTable('invite');
    }
}
