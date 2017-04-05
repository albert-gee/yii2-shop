<?php

use yii\db\Migration;

class m161215_074159_drop_relation_category_column_from_attribute extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('relation_category_id:shop_category_id', 'shop_attribute');
        $this->dropColumn('shop_attribute', 'relation_category_id');
    }

    public function safeDown()
    {
        $this->addColumn('shop_attribute', 'relation_category_id', $this->integer());
        $this->addForeignKey('relation_category_id:shop_category_id',
            'shop_attribute', 'relation_category_id', 'shop_category', 'id');
    }

}
