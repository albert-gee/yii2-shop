<?php

use yii\db\Migration;

class m160530_145154_shop_product_price_change_columns_type extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('shop_product_price', 'price', $this->double(2));
        $this->alterColumn('shop_product_price', 'sale', $this->double(2));
    }

    public function safeDown()
    {
        $this->alterColumn('shop_product_price', 'price', $this->integer());
        $this->alterColumn('shop_product_price', 'sale', $this->integer());
    }
}
