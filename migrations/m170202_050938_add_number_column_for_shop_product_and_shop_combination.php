<?php

use yii\db\Migration;

class m170202_050938_add_number_column_for_shop_product_and_shop_combination extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'number', $this->integer());
        $this->addColumn('shop_combination', 'number', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('shop_combination', 'number');
        $this->dropColumn('shop_product', 'number');
    }

}
