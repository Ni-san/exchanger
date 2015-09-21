<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_184238_test extends Migration
{
    public function up()
    {
        $this->batchInsert(
            'users',
            ['id', 'name', 'sum'],
            [
                [1, 'first',  100],
                [2, 'second', 200],
                [3, 'third',  300],
                [4, 'fourth', 400],
                [5, 'fifth',  500],
            ]
        );
    }

    public function down()
    {
        $this->delete(
            'users',
            ['id' => [1, 2, 3, 4, 5]]
        );
    }
}
