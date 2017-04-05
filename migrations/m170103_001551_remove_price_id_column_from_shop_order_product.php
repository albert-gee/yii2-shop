<?php

use yii\db\Migration;

class m170103_001551_remove_price_id_column_from_shop_order_product extends Migration
{
    public function up()
    {
        $this->dropForeignKey('order_product_price_id_product_price_id', 'shop_order_product');
        $this->dropColumn('shop_order_product', 'price_id');
    }

    public function down()
    {
        $this->addColumn('shop_order_product', 'price_id', $this->integer());
    }

}
