<?php

use yii\db\Migration;

class m161022_133353_add_column_for_selecting_address_or_post_office extends Migration
{
    public function up()
    {
        $this->addColumn('shop_delivery_method', 'show_address_or_post_office', $this->integer(1)->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('shop_delivery_method', 'show_address_or_post_office');
    }
}
