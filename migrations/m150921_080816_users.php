<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_080816_users extends Migration
{
    public function up()
    {
        $this->createTable('users', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'sum' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0',
        ]);
    }

    public function down()
    {
        $this->dropTable('users');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
