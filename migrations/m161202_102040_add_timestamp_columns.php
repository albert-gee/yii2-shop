<?php

use yii\db\Migration;

class m161202_102040_add_timestamp_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product_file', 'creation_time', $this->dateTime());
        $this->addColumn('shop_product_file', 'update_time', $this->dateTime());
    }

    public function safeDown()
    {
        $this->dropColumn('shop_product_file', 'creation_time');
        $this->dropColumn('shop_product_file', 'update_time');
    }

}
