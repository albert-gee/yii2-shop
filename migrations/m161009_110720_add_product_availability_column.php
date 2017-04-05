<?php

use bl\multilang\entities\Language;
use yii\db\Migration;

class m161009_110720_add_product_availability_column extends Migration
{
    public function up()
    {
        $this->createTable('shop_product_availability', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('shop_product_availability_translation', [
            'id' => $this->primaryKey(),
            'availability_id' => $this->integer(2),
            'language_id' => $this->integer(2),
            'title' => $this->string(256),
            'description' => $this->string(256)
        ]);

        $language = Language::find()->where(['lang_id' => 'en-US'])->one();
        $this->insert('shop_product_availability', [
            'id' => 1,
        ]);
        $this->insert('shop_product_availability_translation', [
            'availability_id' => 1,
            'language_id' => $language->id,
            'title' => 'In stock',
        ]);

        $this->insert('shop_product_availability', [
            'id' => 2,
        ]);
        $this->insert('shop_product_availability_translation', [
            'availability_id' => 2,
            'language_id' => $language->id,
            'title' => 'Expected',
        ]);

        $this->insert('shop_product_availability', [
            'id' => 3,
        ]);
        $this->insert('shop_product_availability_translation', [
            'availability_id' => 3,
            'language_id' => $language->id,
            'title' => 'To order',
        ]);

        $this->addColumn('shop_product', 'availability', $this->integer());

        $this->addForeignKey('shop_product_availability:shop_product_availability_id', 'shop_product', 'availability', 'shop_product_availability', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropForeignKey('shop_product_availability:shop_product_availability_id', 'shop_product');
        $this->dropColumn('shop_product', 'availability');
        $this->dropTable('shop_product_availability');
        $this->dropTable('shop_product_availability_translation');
    }
}


