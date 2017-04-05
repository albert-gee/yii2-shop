<?php

use yii\db\Migration;

class m170302_004836_add_default_order_status extends Migration
{
    public function up()
    {
        $this->insert('shop_order_status', [
            'id' => 1,
            'title' => 'Incomplete'
        ]);
    }

    public function down()
    {
        $this->delete('shop_order_status', [
            'id' => 1]);
    }

}
