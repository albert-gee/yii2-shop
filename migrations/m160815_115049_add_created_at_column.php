<?php

use yii\db\Migration;

class m160815_115049_add_created_at_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product', 'creation_time', $this->dateTime());
        $this->addColumn('shop_product', 'update_time', $this->dateTime());
    }

    public function safeDown()
    {
        $this->dropColumn('shop_product', 'update_time');
        $this->dropColumn('shop_product', 'creation_time');
    }
}
