<?php

use yii\db\Migration;

class m160815_085228_add_articulus_column_to_product extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'articulus', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'articulus');
    }
}
