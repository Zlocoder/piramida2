<?php

use yii\db\Migration;

class m170304_103953_create_payment extends Migration
{
    public function up()
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->createTable('invoice', [
                'id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'userId' => $this->integer()->unsigned()->notNull(),
                'userStatus' => $this->string(25)->notNull(),
                'invoiceStatus' => $this->string(25)->notNull(),
                'amount' => $this->decimal(10,2)->notNull(),
                'created' => $this->dateTime()->notNull(),
                'updated' => $this->dateTime()->notNull()
            ]);

            $this->addForeignKey('FK_invoiceUserId', 'invoice', 'userId', 'user', 'id');

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function down()
    {
        $this->dropTable('invoice');
    }
}
