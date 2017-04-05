<?php

use yii\db\Migration;

class m160729_133745_drop_column_export extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_product', 'export');
    }

    public function down()
    {
        $this->addColumn('shop_product', 'export', $this->string());
    }
}
