<?php

use yii\db\Migration;

class m161212_081737_add_articulus_column_to_product_price extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product_price', 'articulus', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('shop_product_price', 'articulus');
    }

}
