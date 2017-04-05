<?php

use yii\db\Migration;

class m161004_113936_delete_columns_from_shop_order_table extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_order', 'first_name');
        $this->dropColumn('shop_order', 'last_name');
        $this->dropColumn('shop_order', 'email');
        $this->dropColumn('shop_order', 'phone');
        $this->dropColumn('shop_order', 'address');

        $this->addColumn('shop_order', 'address_id', $this->integer());

        $this->addForeignKey('shop_order_address:user_address_id', 'shop_order', 'address_id', 'user_address', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropForeignKey('shop_order_address:user_address_id', 'shop_order');

        $this->dropColumn('shop_order', 'address_id');

        $this->addColumn('shop_order', 'first_name', $this->string(255));
        $this->addColumn('shop_order', 'last_name', $this->string(255));
        $this->addColumn('shop_order', 'email', $this->string(255));
        $this->addColumn('shop_order', 'phone', $this->string(17));
        $this->addColumn('shop_order', 'address', $this->string(255));

    }

}
