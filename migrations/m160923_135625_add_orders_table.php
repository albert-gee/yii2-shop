<?php

use yii\db\Migration;

class m160923_135625_add_orders_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_order', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string(255),
            'last_name' => $this->string(255),
            'email' => $this->string(255),
            'phone' => $this->integer(255),
            'address' => $this->string(),
            'status' => $this->string()->notNull()->defaultValue(0),
        ]);

        $this->createTable('shop_order_product', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(11)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'count' => $this->integer(11)->defaultValue(1),
        ]);

        $this->addForeignKey('order_user_id_user_id', 'shop_order', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('order_product_product_id_product_id', 'shop_order_product', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('order_product_order_id_order_id', 'shop_order_product', 'order_id', 'shop_order', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('order_product_order_id_order_id', 'shop_order_product');
        $this->dropForeignKey('order_product_product_id_product_id', 'shop_order_product');
        $this->dropForeignKey('order_user_id_user_id', 'shop_order');

        $this->dropTable('shop_order_product');
        $this->dropTable('shop_order');
    }
}