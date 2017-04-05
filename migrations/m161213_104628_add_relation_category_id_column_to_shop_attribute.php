<?php

use yii\db\Migration;

class m161213_104628_add_relation_category_id_column_to_shop_attribute extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_attribute', 'relation_category_id', $this->integer());

        $this->addForeignKey('relation_category_id:shop_category_id',
            'shop_attribute', 'relation_category_id', 'shop_category', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('relation_category_id:shop_category_id',
            'shop_attribute');
        $this->dropColumn('shop_attribute', 'relation_category_id');

    }

}
