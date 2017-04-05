<?php

use yii\db\Migration;

class m160801_012338_add_product_image_table extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_product', 'cover');
        $this->dropColumn('shop_product', 'thumbnail');
        $this->dropColumn('shop_product', 'menu_item');

        $this->createTable('shop_product_image', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'file_name' => $this->string(),
            'alt' => $this->string()
        ]);

        $this->addForeignKey('shop_product_image:product_id', 'shop_product_image', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropForeignKey('shop_product_image:product_id', 'shop_product_image');

        $this->dropTable('shop_product_image');

        $this->addColumn('shop_product', 'cover', $this->string());
        $this->addColumn('shop_product', 'thumbnail', $this->string());
        $this->addColumn('shop_product', 'menu_item', $this->string());
    }

}
