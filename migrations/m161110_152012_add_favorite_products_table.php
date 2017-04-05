<?php

use yii\db\Migration;

class m161110_152012_add_favorite_products_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_favorite_product', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'user_id' => $this->integer()
        ]);

        $this->addForeignKey('shop_favorite_product_product_id:shop_product_id',
            'shop_favorite_product', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_favorite_product_user_id:user_id',
            'shop_favorite_product', 'user_id', 'user', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('shop_favorite_product_product_id:shop_product_id',
            'shop_favorite_product');
        $this->dropForeignKey('shop_favorite_product_user_id:user_id',
            'shop_favorite_product');

        $this->dropTable('shop_favorite_product');
    }
}
