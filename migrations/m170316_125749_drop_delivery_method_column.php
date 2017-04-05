<?php

use yii\db\Migration;

class m170316_125749_drop_delivery_method_column extends Migration
{
    public function up()
    {
        $this->dropForeignKey('shop_order_delivery_method:shop_delivery_method_id',
            'shop_order');
        $this->dropForeignKey('shop_order_delivery_id:shop_delivery_id',
            'shop_order');

        $this->dropColumn('shop_order', 'delivery_method');

        $this->addForeignKey('shop_order_delivery_id:shop_delivery_id',
            'shop_order', 'delivery_id', 'shop_delivery_method', 'id', 'cascade', 'cascade');


    }

    public function down()
    {
        $this->dropForeignKey('shop_order_delivery_id:shop_delivery_id',
            'shop_order');

        $this->addColumn('shop_order', 'delivery_method', $this->integer());

        $this->addForeignKey('shop_order_delivery_method:shop_delivery_method_id',
            'shop_order', 'delivery_method', 'shop_delivery_method', 'id', 'cascade', 'cascade');

        $this->addForeignKey('shop_order_delivery_id:shop_delivery_id',
            'shop_order', 'delivery_id', 'shop_delivery_method', 'id');
    }
}
