<?php

use yii\db\Migration;

class m170303_073809_add_status_confirmed extends Migration
{

    public function up()
    {
        $this->insert('shop_order_status', [
            'id' => 2,
            'title' => 'Confirmed'
        ]);
    }

    public function down()
    {
        $this->delete('shop_order_status', [
            'id' => 2]);
    }

}
