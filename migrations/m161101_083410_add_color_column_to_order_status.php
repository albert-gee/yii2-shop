<?php

use yii\db\Migration;

class m161101_083410_add_color_column_to_order_status extends Migration
{
    public function up()
    {
        $this->addColumn('shop_order_status', 'color', $this->string());
    }

    public function down()
    {
        $this->dropColumn('shop_order_status', 'color');
    }
}
