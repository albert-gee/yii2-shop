<?php

use yii\db\Migration;

class m160623_113543_created_and_update_time_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product_translation', 'creation_time', 'datetime');
        $this->addColumn('shop_product_translation', 'update_time', 'datetime');
    }

    public function safeDown()
    {
        $this->dropColumn('shop_product_translation', 'update_time');
        $this->dropColumn('shop_product_translation', 'creation_time');
    }
}
