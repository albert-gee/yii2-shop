<?php

use yii\db\Migration;

class m170428_145627_add_shows_column_for_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'shows', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'shows');
    }
}
