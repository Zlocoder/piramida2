<?php

use yii\db\Migration;

class m170304_103953_create_payment extends Migration
{
    public function up()
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->createTable('user_payment', [
                'userId' => $this->integer()->unsigned()->notNull(),
                'pmId' => $this->string(25)->notNull(),
                'payed' => $this->decimal(10, 2),
                'earned' => $this->decimal(10, 2)
            ]);

            $this->addPrimaryKey(null, 'user_payment', 'userId');
            //$this->createIndex('pmId', 'user_payment', 'pmId', true);
            $this->addForeignKey('FK_userPaymentUserId', 'user_payment', 'userId', 'user', 'id');

            $this->insert('user_payment', [
                'userId' => 1,
                'pmId' => 'U13860909',
                'payed' => 0,
                'earned' => 0
            ]);

            $this->createTable('user_status', [
                'userId' => $this->integer()->unsigned()->notNull(),
                'status' => $this->string(25)->notNull(),
                'active' => $this->dateTime()->notNull(),
            ]);

            $this->addPrimaryKey(null, 'user_status', 'userId');
            $this->addForeignKey('FK_userStatusUserId', 'user_status', 'userId', 'user', 'id');

            $this->insert('user_status', [
                'userId' => 1,
                'status' => 'DIAMOND',
                'active' => '9999-12-31 23:59:59',
            ]);

            $this->createTable('invoice', [
                'id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'userId' => $this->integer()->unsigned()->notNull(),
                'userStatus' => $this->string(25)->notNull(),
                'invoiceStatus' => $this->string(25)->notNull(),
                'amount' => $this->decimal(10,2)->notNull(),
                'accrual' => $this->decimal(10, 2)->notNull(),
                'created' => $this->dateTime()->notNull(),
                'updated' => $this->dateTime()->notNull()
            ]);

            $this->addForeignKey('FK_invoiceUserId', 'invoice', 'userId', 'user_payment', 'userId');

            $this->createTable('transaction', [
                'id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'invoiceId' => $this->integer()->unsigned()->notNull(),
                'receiverId' => $this->integer()->unsigned()->notNull(),
                'amount' => $this->decimal(10, 2)->notNull(),
                'status' => $this->string(25)->notNull(),
                'created' => $this->dateTime()->notNull(),
                'updated' => $this->dateTime()->notNull()
            ]);

            $this->addForeignKey('FK_transactionInvoiceId', 'transaction', 'invoiceId', 'invoice', 'id');
            $this->addForeignKey('FK_transactionReceiverId', 'transaction', 'receiverId', 'user_payment', 'userId');

            $this->createTable('payment_history', [
                'userId' => $this->integer()->unsigned()->notNull(),
                'invoiceId' => $this->integer()->unsigned()->notNull(),
                'type' => $this->string(25)->notNull(),
                'status' => $this->string(25)->notNull(),
                'amount' => $this->decimal(10, 2)->notNull(),
                'created' => $this->dateTime()->notNull(),
                'updated' => $this->dateTime()->notNull()
            ]);

            $this->addPrimaryKey(null, 'payment_history', ['userId', 'invoiceId']);
            $this->addForeignKey('FK_paymentHistoryUserId', 'payment_history', 'userId', 'user_payment', 'userId');
            $this->addForeignKey('FK_paymentHistoryInvoiceId', 'payment_history', 'invoiceId', 'invoice', 'id');

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
            $this->dropTable('payment_history');
            $this->dropTable('transaction');
            $this->dropTable('invoice');
            $this->dropTable('user_status');
            $this->dropTable('user_payment');

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
        }
    }
}
