<?php

use yii\db\Migration;

class m160530_122813_shop_add_column_vendor_id_to_product extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product', 'vendor_id', $this->integer());
        $this->addForeignKey('shop_product:vendor_id', 'shop_product', 'vendor_id', 'shop_vendor', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('shop_product:vendor_id', 'shop_product');
        $this->dropColumn('shop_product', 'vendor_id');
    }
}
