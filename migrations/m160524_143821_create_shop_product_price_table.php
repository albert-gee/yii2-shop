<?php

use yii\db\Migration;

/**
 * Handles the creation for table `shop_product_price_table`.
 */
class m160524_143821_create_shop_product_price_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('shop_product_price', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'price' => $this->integer(),
            'sale' => $this->integer(),
            'sale_type_id' => $this->integer()
        ]);

        $this->addForeignKey(
            'shop_product_price:product_id',
            'shop_product_price',
            'product_id',
            'shop_product',
            'id',
            'cascade'
        );

        $this->addForeignKey(
            'shop_product_price:sale_type_id',
            'shop_product_price',
            'sale_type_id',
            'shop_product_sale_type',
            'id'
        );

        $this->createTable('shop_product_price_translation', [
            'id' => $this->primaryKey(),
            'price_id' => $this->integer(),
            'language_id' => $this->integer(),
            'title' => $this->string()
        ]);

        $this->addForeignKey(
            'shop_product_price_translation:price_id',
            'shop_product_price_translation',
            'price_id',
            'shop_product_price',
            'id',
            'cascade'
        );

        $this->addForeignKey(
            'shop_product_price_translation:language_id',
            'shop_product_price_translation',
            'language_id',
            'language',
            'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('shop_product_price:product_id', 'shop_product_price');
        $this->dropForeignKey('shop_product_price:sale_type_id', 'shop_product_price');

        $this->dropForeignKey('shop_product_price_translation:price_id', 'shop_product_price_translation');
        $this->dropForeignKey('shop_product_price_translation:language_id', 'shop_product_price_translation');

        $this->dropTable('shop_product_price');
        $this->dropTable('shop_product_price_translation');
    }
}
