<?php

use yii\db\Migration;

class m160815_120911_add_owner_field_to_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'owner', 'string');
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'owner');
    }
}
