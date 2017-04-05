<?php

use yii\db\Migration;

class m161101_084809_add_color_to_order_status extends Migration
{
    public function up()
    {
        $this->update('shop_order_status', ['color' => '#999999'], ['id' => 1]);
        $this->update('shop_order_status', ['color' => '#990000'], ['id' => 2]);
    }

    public function down()
    {
        return true;
    }
}
