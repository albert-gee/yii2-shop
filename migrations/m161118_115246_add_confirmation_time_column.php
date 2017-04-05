<?php

use yii\db\Migration;

class m161118_115246_add_confirmation_time_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_order', 'confirmation_time', $this->dateTime());
    }

    public function safeDown()
    {
        $this->dropColumn('shop_order', 'confirmation_time');
    }
}
