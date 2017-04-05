<?php

use yii\db\Migration;

class m170201_002713_add_cascade_for_shop_attribute extends Migration
{
    public function up()
    {
        $this->dropForeignKey('shop_attribute_translation:shop_attribute_attr_id',
            'shop_attribute_translation');
        $this->addForeignKey('shop_attribute_translation:shop_attribute_attr_id',
            'shop_attribute_translation', 'attr_id', 'shop_attribute', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('shop_attribute_translation:shop_attribute_attr_id',
            'shop_attribute_translation');
        $this->addForeignKey('shop_attribute_translation:shop_attribute_attr_id',
            'shop_attribute_translation', 'attr_id', 'shop_attribute', 'id');
    }
}
