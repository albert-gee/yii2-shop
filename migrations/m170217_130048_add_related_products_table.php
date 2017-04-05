<?php

use yii\db\Migration;

class m170217_130048_add_related_products_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_related_product', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'related_product_id' => $this->integer()
        ]);

        $this->addForeignKey('shop_related_product_product_id:shop_product_id',
            'shop_related_product', 'product_id',
            'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_related_product_related_product:shop_product_id',
            'shop_related_product', 'related_product_id',
            'shop_product', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropTable('shop_related_product');
    }

}
