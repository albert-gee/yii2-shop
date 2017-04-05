<?php

use yii\db\Migration;

class m170315_175641_add_show_field_to_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'show', $this->boolean()->defaultValue(true));
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'show');
    }
}
