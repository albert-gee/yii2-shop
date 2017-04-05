<?php

use yii\db\Migration;

class m170312_110849_add_order_translation_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_order_status_translation', [
            'id' =>$this->primaryKey(),
            'order_status_id' => $this->integer(),
            'language_id' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->string()
        ]);

        $this->dropColumn('shop_order_status', 'title');

        $this->addForeignKey('shop_order_status_translation_language_id:language_id',
            'shop_order_status_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_order_status_translation_status_id:shop_order_status_id',
            'shop_order_status_translation', 'order_status_id', 'shop_order_status', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropForeignKey('shop_order_status_translation_language_id:language_id', 'shop_order_status_translation');
        $this->dropForeignKey('shop_order_status_translation_status_id:shop_order_status_id', 'shop_order_status_translation');

        $this->dropTable('shop_order_status_translation');

        $this->update('shop_order_status', ['title' => 'Incomplete'], ['id' => 1]);
        $this->update('shop_order_status', ['title' => 'Confirmed'], ['id' => 2]);
    }
}
