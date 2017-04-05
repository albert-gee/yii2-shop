<?php

use yii\db\Migration;

class m160928_235822_alter_order_product extends Migration
{
    public function up()
    {

        $this->dropForeignKey('shop_order_status_shop_order_status_id',
            'shop_order');
        $this->addForeignKey('shop_order_status_shop_order_status_id',
            'shop_order', 'status', 'shop_order_status', 'id', 'cascade', 'cascade');


        $this->addColumn('shop_order_product',
            'price_id', $this->integer(11)
        );

        $this->addForeignKey('order_product_price_id_product_price_id', 'shop_order_product', 'price_id', 'shop_price', 'id', 'cascade', 'cascade');

        $this->dropForeignKey('order_product_order_id_order_id', 'shop_order_product');
        $this->addForeignKey('order_product_order_id_order_id', 'shop_order_product', 'order_id', 'shop_order', 'id', 'cascade', 'cascade');
        $this->dropForeignKey('order_user_id_user_id', 'shop_order');
        $this->addForeignKey('order_user_id_user_id', 'shop_order', 'user_id', 'user', 'id', 'cascade', 'cascade');
        return true;
    }

    public function down()
    {
        $this->dropForeignKey('order_product_price_id_product_price_id', 'shop_order_product');
        $this->dropColumn('shop_order_product', 'price_id');
    }

}