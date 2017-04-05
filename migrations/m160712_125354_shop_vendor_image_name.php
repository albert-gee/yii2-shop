<?php

use yii\db\Migration;

class m160712_125354_shop_vendor_image_name extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_vendor', 'image_name', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('shop_vendor', 'image_name');
    }
}
