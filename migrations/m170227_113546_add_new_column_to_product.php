<?php

use yii\db\Migration;

class m170227_113546_add_new_column_to_product extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'new', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'new');
    }
}
