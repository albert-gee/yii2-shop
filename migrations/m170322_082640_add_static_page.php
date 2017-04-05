<?php

use yii\db\Migration;

class m170322_082640_add_static_page extends Migration
{
    public function up()
    {
        $this->insert('static_page', [
            'key' => 'cart',
        ]);
    }

    public function down()
    {
        $this->delete('static_page', [
            'key' => 'cart'
        ]);
    }

}
