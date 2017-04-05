<?php

use yii\db\Migration;

class m170326_144044_create_table_shop_order_product_additional_product extends Migration
{
    public function up()
    {
        $this->createTable('shop_order_product_additional_product', [
            'id' => $this->primaryKey(),
            'order_product_id' => $this->integer(),
            'additional_product_id' => $this->integer()
        ]);

        $this->addForeignKey('order_product_id:shop_order_product_id',
            'shop_order_product_additional_product', 'order_product_id', 'shop_order_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_order_product_additional_product:shop_product_id',
            'shop_order_product_additional_product', 'additional_product_id', 'shop_product', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('shop_order_product_additional_product:shop_product_id',
            'shop_order_product_additional_product');
        $this->dropForeignKey('order_product_id:shop_order_product_id',
            'shop_order_product_additional_product');

        $this->dropTable('shop_order_product_additional_product');
    }
}
