<?php

use yii\db\Migration;

/**
 * Handles the creation for table `product_tables`.
 */
class m160519_115056_create_product_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shop_product', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'price' => $this->string(),
            'imageFile' => $this->text()
        ]);
        $this->createTable('shop_product_translation', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'full-text' => 'longtext',
            'characteristics' => $this->text(),
            'dose' => $this->text(),
            'seoUrl' => $this->text(),
            'seoTitle' => $this->text(),
            'seoDescription' => $this->text(),
            'seoKeywords' => $this->text()
        ]);
        $this->addForeignKey('product_product_translation', 'shop_product_translation', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('product_language', 'shop_product_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
        $this->addForeignKey('product_category', 'shop_product', 'category_id', 'shop_category', 'id', 'cascade', 'cascade');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('product_product_translation', 'shop_product_translation');
        $this->dropForeignKey('product_language', 'shop_product_translation');
        $this->dropForeignKey('product_category', 'shop_product');

        $this->dropTable('shop_product_translation');
        $this->dropTable('shop_product');
    }
}
