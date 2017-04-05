<?php

use yii\db\Migration;

class m160529_183827_shop_product_image_name extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product', 'image_name', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('shop_product', 'image_name');
    }
}
