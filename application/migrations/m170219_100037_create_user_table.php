<?php

use yii\db\Migration;
use yii\base\Exception;

class m170219_100037_create_user_table extends Migration
{
    public function up()
    {
        $transaction = $this->db->beginTransaction();

        try {
            $this->createTable('user', [
                'id INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
                'firstname' => $this->string(25)->notNull(),
                'lastname' => $this->string(25)->notNull(),
                'login' => $this->string(25)->notNull(),
                'password' => $this->string(60)->notNull(),
                'email' => $this->string(100)->notNull(),
                'created' => $this->dateTime()->notNull(),
                'updated' => $this->dateTime()->notNull(),
            ]);

            $this->createIndex('login', 'user', 'login', true);
            $this->createIndex('email', 'user', 'email', true);

            $this->insert('user', [
                'id' => 1,
                'firstname' => 'Administrator',
                'lastname' => 'Administrator',
                'login' => 'admin',
                'password' => \Yii::$app->security->generatePasswordHash('admin'),
                'email' => 'admin@site.com',
                'created' => new \yii\db\Expression('NOW()'),
                'updated' => new \yii\db\Expression('NOW()')
            ]);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }
    }

    public function down()
    {
        $this->dropTable('user');
    }
}
