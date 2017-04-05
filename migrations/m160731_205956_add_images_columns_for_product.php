<?php

use yii\db\Migration;

class m160731_205956_add_images_columns_for_product extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'cover', $this->string());
        $this->addColumn('shop_product', 'thumbnail', $this->string());
        $this->addColumn('shop_product', 'menu_item', $this->string());
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'menu_item');
        $this->dropColumn('shop_product', 'thumbnail');
        $this->dropColumn('shop_product', 'cover');
    }
}
