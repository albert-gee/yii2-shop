<?php

use yii\db\Migration;

class m160906_104957_add_attribute_tables extends Migration
{
    public function up()
    {
        /*CREATING TABLES*/
        $this->createTable('shop_attribute', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ]);

        $this->createTable('shop_attribute_translation', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'language_id' => $this->integer(),
            'attr_id' => $this->integer()
        ]);

        $this->createTable('shop_attribute_type', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);

        $this->createTable('shop_attribute_value', [
            'id' => $this->primaryKey(),
            'attribute_id' => $this->integer(),
        ]);

        $this->createTable('shop_attribute_value_translation', [
            'id' => $this->primaryKey(),
            'value_id' => $this->integer(),
            'title' => $this->string(),
            'value' => $this->string(),
            'language_id' => $this->integer(),
        ]);

        $this->createTable('shop_attribute_value_color_texture', [
            'id' => $this->primaryKey(),
            'color' => $this->string(),
            'texture' => $this->string()
        ]);

        /*CREATING FOREIGN KEYS*/
        $this->addForeignKey('shop_attribute_shop_attribute_type', 'shop_attribute', 'type_id', 'shop_attribute_type', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_attribute_value_shop_attribute', 'shop_attribute_value', 'attribute_id', 'shop_attribute', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_attribute_value_translation_shop_attribute_value', 'shop_attribute_value_translation', 'value_id', 'shop_attribute_value', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_attribute_value_translation_language', 'shop_attribute_value_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_attribute_translation_language', 'shop_attribute_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');

    }

    public function down()
    {

        $this->dropForeignKey('shop_attribute_translation_language', 'shop_attribute_translation');
        $this->dropForeignKey('shop_attribute_value_translation_language', 'shop_attribute_value_translation');

        $this->dropForeignKey('shop_attribute_shop_attribute_type', 'shop_attribute');
        $this->dropForeignKey('shop_attribute_value_shop_attribute', 'shop_attribute_value');
        $this->dropForeignKey('shop_attribute_value_translation_shop_attribute_value', 'shop_attribute_value_translation');

        $this->dropTable('shop_attribute_value_color_texture');
        $this->dropTable('shop_attribute_value_translation');
        $this->dropTable('shop_attribute_value');
        $this->dropTable('shop_attribute_type');
        $this->dropTable('shop_attribute_translation');
        $this->dropTable('shop_attribute');
    }
}
