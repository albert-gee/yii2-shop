<?php

use yii\db\Migration;

class m160719_124749_add_price_column_to_shop_product extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'price', 'integer');
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'price');
    }
}
