<?php

use yii\db\Migration;

class m170116_120241_add_vendor_description_translation_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_vendor_translation', [
            'id' => $this->primaryKey(),
            'vendor_id' => $this->integer(),
            'language_id' => $this->integer(),
            'description' => $this->string()
        ]);

        $this->addForeignKey('vendor_id:shop_vendor_id', 'shop_vendor_translation', 'vendor_id', 'shop_vendor', 'id');
        $this->addForeignKey('language_id:language_id', 'shop_vendor_translation', 'language_id', 'language', 'id');
    }

    public function down()
    {
        $this->dropTable('shop_vendor_translation');
    }
}
