<?php

use yii\db\Migration;

class m161204_154347_add_shop_product_image_translation_table extends Migration
{
    public function up()
    {
        $this->createTable('shop_product_image_translation', [
            'id' => $this->primaryKey(),
            'image_id' => $this->integer(),
            'language_id' => $this->integer(),
            'alt' => $this->string()
        ]);

        $this->dropColumn('shop_product_image', 'alt');

        $this->addForeignKey('shop_product_image_translation:shop_product_image_id',
            'shop_product_image_translation', 'image_id', 'shop_product_image', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_image_translation:language_id',
            'shop_product_image_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('shop_product_image_translation:shop_product_image_id',
            'shop_product_image_translation');
        $this->dropForeignKey('shop_product_image_translation:language_id',
            'shop_product_image_translation');

        $this->addColumn('shop_product_image', 'alt', $this->integer());
        $this->dropTable('shop_product_image_translation');
    }

}
