<?php

use yii\db\Migration;

class m161202_162035_add_position_column_for_shop_product_image_and_shop_product_price extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product_price', 'position', $this->integer());
        $this->addColumn('shop_product_image', 'position', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('shop_product_price', 'position');
        $this->dropColumn('shop_product_image', 'position');
    }

}
