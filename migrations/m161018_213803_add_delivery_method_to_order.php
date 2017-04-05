<?php

use yii\db\Migration;

class m161018_213803_add_delivery_method_to_order extends Migration
{
    public function up()
    {
        $this->createTable('shop_delivery_method', [
            'id' => $this->primaryKey(4),
            'image_name' => $this->string(),
        ]);
        $this->createTable('shop_delivery_method_translation', [
            'id' => $this->primaryKey(4),
            'delivery_method_id' => $this->integer(4),
            'language_id' =>$this->integer(4),
            'title' => $this->string(),
            'description' => $this->string()
        ]);

        $this->addColumn('shop_order', 'delivery_method', $this->integer());

        $this->addForeignKey('delivery_translation_delivery_id:delivery_id',
            'shop_delivery_method_translation', 'delivery_method_id',
            'shop_delivery_method', 'id', 'cascade', 'cascade');
        $this->addForeignKey('delivery_translation_language_id:langiage_id',
            'shop_delivery_method_translation', 'language_id',
            'language', 'id', 'cascade', 'cascade');

        $this->addForeignKey('shop_order_delivery_method:shop_delivery_method_id',
            'shop_order', 'delivery_method', 'shop_delivery_method', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('shop_order_delivery_method:shop_delivery_method_id',
            'shop_order');
        $this->dropForeignKey('delivery_translation_language_id:langiage_id',
            'shop_delivery_method_translation');
        $this->dropForeignKey('delivery_translation_delivery_id:delivery_id',
            'shop_delivery_method_translation');

        $this->dropColumn('shop_order', 'delivery_method');

        $this->dropTable('shop_delivery_method_translation');
        $this->dropTable('shop_delivery_method');
    }
}
