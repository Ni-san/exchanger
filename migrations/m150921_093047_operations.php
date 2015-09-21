<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_093047_operations extends Migration
{
    public function up()
    {
        $this->createTable('operations', [
            'id' => Schema::TYPE_PK,
            'recipient' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sender' => Schema::TYPE_INTEGER . ' NOT NULL',
            'sum' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('operations');
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
