<?php

use yii\db\Migration;

class m170111_131119_change_shop_order_product_foreign_key extends Migration
{
    public function up()
    {
        $this->dropForeignKey('combination_id:shop_combination_id',
            'shop_order_product');
        $this->addForeignKey('combination_id:shop_combination_id',
            'shop_order_product', 'combination_id', 'shop_combination', 'id', 'SET NULL', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('combination_id:shop_product_combination_id', 'shop_order_product');
        $this->addForeignKey('combination_id:shop_combination_id',
            'shop_order_product', 'combination_id', 'shop_combination', 'id');
    }

}
