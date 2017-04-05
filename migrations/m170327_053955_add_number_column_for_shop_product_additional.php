<?php

use yii\db\Migration;

class m170327_053955_add_number_column_for_shop_product_additional extends Migration
{
    public function up()
    {
        $this->addColumn('shop_order_product_additional_product', 'number', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('shop_order_product_additional_product', 'number');
    }

}
