<?php

use yii\db\Migration;

class m170102_230323_rename_articulus_to_sku_in_shop_product extends Migration
{
    public function up()
    {
        $this->renameColumn('shop_product', 'articulus', 'sku');
    }

    public function down()
    {
        $this->renameColumn('shop_product', 'sku', 'articulus');
    }
}
