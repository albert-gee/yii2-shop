<?php

use yii\db\Migration;

class m161229_112132_change_price_and_combination_tables extends Migration
{
    public function up()
    {

        $this->createTable('shop_price_discount_type', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ]);
        $this->insert('shop_price_discount_type', [
            'title' => 'money'
        ]);
        $this->insert('shop_price_discount_type', [
            'title' => 'percent'
        ]);

        $this->createTable('shop_combination', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'sku' => $this->string(),
            'default' => $this->boolean()
        ]);
        $this->addForeignKey('shop_combination:shop_product_id', 'shop_combination', 'product_id',
            'shop_product', 'id', 'cascade', 'cascade');

        $this->createTable('shop_combination_translation', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'language_id' => $this->integer(),
            'description' => $this->string()
        ]);
        $this->addForeignKey('shop_combination_translation:shop_combination_id',
            'shop_combination_translation', 'combination_id', 'shop_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_combination_translation:language_id',
            'shop_combination_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');

        $this->createTable('shop_price', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'user_group_id' => $this->integer(),

            'price' => $this->float(),
            'discount' => $this->float(),
            'discount_type_id' => $this->integer(),

            'inequality_sign' => $this->string(),
            'number' => $this->integer()
        ]);

        $this->addForeignKey('shop_price:shop_combination_id',
            'shop_price', 'combination_id', 'shop_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_price:user_group_id',
            'shop_price', 'user_group_id', 'user_group', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_price:shop_price_discount_type_id',
            'shop_price', 'discount_type_id', 'shop_price_discount_type', 'id', 'cascade', 'cascade');

        $this->createTable('shop_combination_attribute', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'attribute_id' => $this->integer(),
            'attribute_value_id' => $this->integer()
        ]);
        $this->addForeignKey('shop_combination_attribute:shop_combination_id',
            'shop_combination_attribute', 'combination_id', 'shop_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_combination_attribute:shop_attribute_id',
            'shop_combination_attribute', 'attribute_id', 'shop_attribute', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_combination_attribute:shop_attribute_value_id',
            'shop_combination_attribute', 'attribute_value_id', 'shop_attribute_value', 'id', 'cascade', 'cascade');

        $this->createTable('shop_combination_image', [
            'id' => $this->primaryKey(),
            'combination_id' => $this->integer(),
            'product_image_id' => $this->integer()
        ]);
        $this->addForeignKey('shop_combination_image:shop_combination_id',
            'shop_combination_image', 'combination_id', 'shop_combination', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_combination_image:shop_product_image_id',
            'shop_combination_image', 'product_image_id', 'shop_product_image', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropTable('shop_price');
        $this->dropTable('shop_price_discount_type');

        $this->dropTable('shop_combination_image');
        $this->dropTable('shop_combination_attribute');

        $this->dropTable('shop_combination_translation');
        $this->dropTable('shop_combination');

    }

}
