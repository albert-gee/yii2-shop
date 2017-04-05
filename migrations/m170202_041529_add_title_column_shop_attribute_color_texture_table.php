<?php

use yii\db\Migration;

class m170202_041529_add_title_column_shop_attribute_color_texture_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_attribute_value_color_texture', 'title', $this->string());
    }

    public function down()
    {
        $this->dropColumn('shop_attribute_value_color_texture', 'title');
    }
}
