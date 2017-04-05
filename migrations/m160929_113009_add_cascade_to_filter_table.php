<?php

use yii\db\Migration;

class m160929_113009_add_cascade_to_filter_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('shop_filters_category_id_category_id', 'shop_filters');
        $this->addForeignKey('shop_filters_category_id_category_id', 'shop_filters', 'category_id', 'shop_category', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        return true;
    }

}
