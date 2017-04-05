<?php

use yii\db\Migration;

class m161013_124914_add_shop_currency_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_currency', [
            'id' => $this->primaryKey(),
            'value' => $this->integer()->notNull(),
            'date' => $this->date()
        ]);
    }

    public function down()
    {
        $this->dropTable('shop_currency');
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
