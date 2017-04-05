<?php

use yii\db\Migration;

class m170314_102005_timestamp_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('shop_order', 'creation_time', $this->dateTime());
        $this->addColumn('shop_order', 'update_time', $this->dateTime());
    }

    public function down()
    {
        $this->dropColumn('shop_order', 'creation_time');
        $this->dropColumn('shop_order', 'update_time');
    }
}
