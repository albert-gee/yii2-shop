<?php

use yii\db\Migration;

class m170202_045823_add_availability_column_for_shop_combination_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_combination', 'availability', $this->integer());

        $this->addForeignKey('shop_combination:shop_product_availability',
            'shop_combination', 'availability', 'shop_product_availability', 'id', 'cascade', 'cascade');
    }

    public function down()
    {
        $this->dropForeignKey('shop_combination:shop_product_availability',
            'shop_combination');
        $this->dropColumn('shop_combination', 'availability');
    }

}
