<?php

use yii\db\Migration;

class m170324_125305_add_combination_id_to_shop_order_product_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_order_product', 'combination_id', $this->integer());

        $this->addForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product', 'combination_id', 'shop_combination', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product');

        $this->dropColumn('shop_order_product', 'combination_id');
    }

}