<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_184238_test extends Migration
{
    public function up()
    {
        $this->batchInsert(
            'users',
            ['name', 'sum'],
            [
                ['first',  100],
                ['second', 200],
                ['third',  300],
                ['fourth', 400],
                ['fifth',  500],
            ]
        );
    }

    public function down()
    {
        $this->delete(
            'users',
            [
                'name' => [
                    'first',
                    'second',
                    'third',
                    'fourth',
                    'fifth',
                ]
            ]
        );
    }
}
