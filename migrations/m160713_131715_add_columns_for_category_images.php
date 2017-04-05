<?php

use yii\db\Migration;

class m160713_131715_add_columns_for_category_images extends Migration
{
    public function up()
    {
        $this->addColumn('shop_category', 'cover', 'string');
        $this->addColumn('shop_category', 'thumbnail', 'string');
        $this->addColumn('shop_category', 'menu_item', 'string');
    }

    public function down()
    {
        $this->dropColumn('shop_category', 'cover');
        $this->dropColumn('shop_category', 'thumbnail');
        $this->dropColumn('shop_category', 'menu_item');
    }
}
