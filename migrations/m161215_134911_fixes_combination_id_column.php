<?php

use yii\db\Migration;

class m161215_134911_fixes_combination_id_column extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product');

        $this->dropColumn('shop_order_product', 'combination_id');

        $this->addColumn('shop_order_product', 'combination_id', $this->integer());

        $this->addForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product', 'combination_id', 'shop_combination', 'id', 'cascade', 'cascade');


    }

    public function safeDown()
    {
        $this->dropForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product');

        $this->dropColumn('shop_order_product', 'combination_id');

        $this->addColumn('shop_order_product', 'combination_id', $this->integer());

        $this->addForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product', 'combination_id', 'shop_combination', 'id');

    }
}