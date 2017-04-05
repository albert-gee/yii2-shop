<?php

use yii\db\Migration;

class m160529_195403_shop_product_imageFile_column_remove extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('shop_product', 'imageFile');
    }

    public function safeDown()
    {
        $this->addColumn('shop_product', 'imageFile', $this->string());
    }
}
