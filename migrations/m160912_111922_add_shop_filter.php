<?php

use yii\db\Migration;

class m160912_111922_add_shop_filter extends Migration
{
    public function safeUp()
    {
        $this->createTable('shop_filters', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'filter_by_vendor' => $this->boolean(),
            'filter_by_country' => $this->boolean()
        ]);

        $this->addForeignKey('shop_filters_category_id_category_id', 'shop_filters', 'category_id', 'shop_category', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('shop_filters_category_id_category_id', 'shop_filters');
        $this->dropTable('shop_filters');
    }

}
