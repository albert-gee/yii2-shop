<?php

use yii\db\Migration;

class m160529_184116_shop_product_price_column_remove extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('shop_product', 'price');
    }

    public function safeDown()
    {
        $this->addColumn('shop_product', 'price', $this->string());
    }
}
