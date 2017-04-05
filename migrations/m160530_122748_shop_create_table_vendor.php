<?php

use yii\db\Migration;

class m160530_122748_shop_create_table_vendor extends Migration
{
    public function safeUp()
    {
        $this->createTable('shop_vendor', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('shop_vendor');
    }
}
