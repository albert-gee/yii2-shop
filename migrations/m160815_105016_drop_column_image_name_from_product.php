<?php

use yii\db\Migration;

class m160815_105016_drop_column_image_name_from_product extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_product', 'image_name');
    }

    public function down()
    {
        $this->addColumn('shop_product', 'image_name', $this->string());
    }
}
