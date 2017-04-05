<?php

use yii\db\Migration;

class m161103_132136_alter_articulus_column_in_product_table extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_product', 'articulus', 'string');
    }

    public function down()
    {
        $this->alterColumn('shop_product', 'articulus', 'integer');

        return false;
    }
}
