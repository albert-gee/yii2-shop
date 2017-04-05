<?php

use yii\db\Migration;

class m161226_024214_add_user_group_id_columns_for_price extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product_price', 'user_group_id', $this->integer());
        $this->addColumn('shop_product_combination', 'user_group_id', $this->integer());

        $this->addForeignKey('shop_product_price:user_group_id',
            'shop_product_price', 'user_group_id', 'user_group', 'id');
        $this->addForeignKey('shop_product_combination:user_group_id',
            'shop_product_combination', 'user_group_id', 'user_group', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('shop_product_combination:user_group_id',
            'shop_product_combination');
        $this->dropForeignKey('shop_product_price:user_group_id',
            'shop_product_price');

        $this->dropColumn('shop_product_combination', 'user_group_id');
        $this->dropColumn('shop_product_price', 'user_group_id');
    }
}
