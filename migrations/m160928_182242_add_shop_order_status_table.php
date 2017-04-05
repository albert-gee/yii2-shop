<?php

use yii\db\Migration;

class m160928_182242_add_shop_order_status_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_order_status', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ]);
        $this->dropColumn('shop_order', 'status');
        $this->addColumn('shop_order', 'status', $this->integer());
        $this->addForeignKey('shop_order_status_shop_order_status_id',
            'shop_order', 'status', 'shop_order_status', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('shop_order_status_shop_order_status_id',
            'shop_order');

        $this->dropColumn('shop_order', 'status');
        $this->addColumn('shop_order', 'status', $this->string());

        $this->dropTable('shop_order_status');
    }

}
