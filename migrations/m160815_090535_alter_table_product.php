<?php

use yii\db\Migration;

class m160815_090535_alter_table_product extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_product', 'price', $this->double());
    }

    public function down()
    {
        $this->alterColumn('shop_product', 'price', $this->integer());
    }


}
