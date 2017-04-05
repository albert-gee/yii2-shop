<?php

use yii\db\Migration;

class m161219_145941_add_articulus_column_for_shop_product_combination_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product_combination', 'articulus', $this->string());
    }

    public function down()
    {
        $this->dropColumn('shop_product_combination', 'articulus');
    }

}
