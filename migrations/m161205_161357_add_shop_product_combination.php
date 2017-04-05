<?php

use yii\db\Migration;

class m161205_161357_add_shop_product_combination extends Migration
{
    public function safeUp()
    {
        $this->createTable('shop_product_combination', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'price' => $this->float(),
            'sale' => $this->float(),
            'sale_type_id' => $this->integer(),
            'default' => $this->boolean()
        ]);
        $this->addForeignKey('shop_product_combination:shop_product_id',
            'shop_product_combination', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_combination:shop_product_sale_type_id',
            'shop_product_combination', 'sale_type_id', 'shop_product_sale_type', 'id', 'cascade', 'cascade');

        $this->createTable('shop_product_combination_image', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'product_image_id' => $this->integer()
        ]);
        $this->addForeignKey('shop_product_combination_image:shop_product_combination_id',
            'shop_product_combination_image', 'combination_id', 'shop_product_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_combination_image:shop_product_image_id',
            'shop_product_combination_image', 'product_image_id', 'shop_product_image', 'id', 'cascade', 'cascade');

        $this->createTable('shop_product_combination_attribute', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'attribute_id' => $this->integer(),
            'attribute_value_id' => $this->integer()
        ]);
        $this->addForeignKey('shop_product_combination_attribute:shop_product_combination_id',
            'shop_product_combination_attribute', 'combination_id', 'shop_product_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_combination_attribute:shop_attribute_id',
            'shop_product_combination_attribute', 'attribute_id', 'shop_attribute', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_combination_attribute:shop_attribute_value_id',
            'shop_product_combination_attribute', 'attribute_value_id', 'shop_attribute_value', 'id', 'cascade', 'cascade');

    }

    public function safeDown()
    {
        $this->dropForeignKey('shop_product_combination_attribute:shop_attribute_value_id',
            'shop_product_combination_attribute');
        $this->dropForeignKey('shop_product_combination_attribute:shop_attribute_id',
            'shop_product_combination_attribute');
        $this->dropForeignKey('shop_product_combination_attribute:shop_product_combination_id',
            'shop_product_combination_attribute');

        $this->dropTable('shop_product_combination_attribute');

        $this->dropForeignKey('shop_product_combination_image:shop_product_image_id',
            'shop_product_combination_image');
        $this->dropForeignKey('shop_product_combination_image:shop_product_combination_id',
            'shop_product_combination_image');

        $this->dropTable('shop_product_combination_image');

        $this->dropForeignKey('shop_product_combination:shop_product_sale_type_id',
            'shop_product_combination');
        $this->dropForeignKey('shop_product_combination:shop_product_id',
            'shop_product_combination');

        $this->dropTable('shop_product_combination');

    }

}
