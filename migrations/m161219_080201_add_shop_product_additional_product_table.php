<?php

use yii\db\Migration;

class m161219_080201_add_shop_product_additional_product_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_product_additional_product', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'additional_product_id' => $this->integer()
        ]);

        $this->addForeignKey('product_id:shop_product_id',
            'shop_product_additional_product', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('additional_product_id:shop_product_id',
            'shop_product_additional_product', 'additional_product_id', 'shop_product', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('additional_product_id:shop_product_id',
            'shop_product_additional_product');
        $this->dropForeignKey('product_id:shop_product_id',
            'shop_product_additional_product');

        $this->dropTable('shop_product_additional_product');
    }
}
