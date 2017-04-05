<?php

use yii\db\Migration;

/**
 * Handles the creation for table `category_tables`.
 */
class m160519_114950_create_category_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shop_category', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
        ]);
        $this->createTable('shop_category_translation', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'language_id' => $this->integer()->notNull(),
            'title' => $this->string(),
            'description' => $this->text(),
            'seoUrl' => $this->text(),
            'seoTitle' => $this->text(),
            'seoDescription' => $this->text(),
            'seoKeywords' => $this->text()
        ]);
        $this->addForeignKey('shop_category_shop_category_translation', 'shop_category_translation', 'category_id', 'shop_category', 'id', 'cascade', 'cascade');
        $this->addForeignKey('category_language', 'shop_category_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('shop_category_shop_category_translation', 'shop_category_translation');
        $this->dropForeignKey('category_language', 'shop_category_translation');

        $this->dropTable('shop_category_translation');
        $this->dropTable('shop_category');
    }
}
