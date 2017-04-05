<?php

use yii\db\Migration;

class m161128_010311_alter_table_shop_currency extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_currency', 'value', $this->double());
    }

    public function down()
    {
        $this->alterColumn('shop_currency', 'value', $this->integer());
    }

}
