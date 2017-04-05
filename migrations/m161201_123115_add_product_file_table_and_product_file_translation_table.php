<?php

use yii\db\Migration;

class m161201_123115_add_product_file_table_and_product_file_translation_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('shop_product_file', [
            'id' => $this->primaryKey(),
            'file' => $this->string(),
            'product_id' => $this->integer()
        ]);

        $this->createTable('shop_product_file_translation', [
            'id' => $this->primaryKey(),
            'product_file_id' => $this->integer(),
            'language_id' => $this->integer(),
            'type' => $this->string(),
            'description' => $this->string()
        ]);

        $this->addForeignKey('shop_product_file:shop_product_id',
            'shop_product_file', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_file_translation:shop_product_file_id',
            'shop_product_file_translation', 'product_file_id', 'shop_product_file', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_file_translation:language_id',
            'shop_product_file_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
    }

    public function safeDown()
    {
        $this->dropForeignKey('shop_product_file:shop_product_id',
            'shop_product_file');
        $this->dropForeignKey('shop_product_file_translation:shop_product_file_id',
            'shop_product_file_translation');
        $this->dropForeignKey('shop_product_file_translation:language_id',
            'shop_product_file_translation');

        $this->dropTable('shop_product_file');
        $this->dropTable('shop_product_file_translation');
    }

}
