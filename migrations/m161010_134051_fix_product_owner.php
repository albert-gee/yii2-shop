<?php

use yii\db\Migration;

class m161010_134051_fix_product_owner extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_product', 'owner');
        $this->addColumn('shop_product', 'owner', $this->integer());
        $this->addForeignKey('shop_product_owner:user_id', 'shop_product', 'owner', 'user', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('shop_product_owner:user_id', 'shop_product');
        $this->dropColumn('shop_product', 'owner');
        $this->addColumn('shop_product', 'owner', $this->string());
    }

}
