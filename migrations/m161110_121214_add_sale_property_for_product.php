<?php

use yii\db\Migration;

class m161110_121214_add_sale_property_for_product extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'sale', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'sale');
    }
}
