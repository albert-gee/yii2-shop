<?php

use yii\db\Migration;

class m160729_132544_drop_column_characteristics_and_doses extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_product_translation', 'characteristics');
        $this->dropColumn('shop_product_translation', 'dose');
    }

    public function down()
    {
        $this->addColumn('shop_product_translation', 'dose', $this->string());
        $this->addColumn('shop_product_translation', 'characteristics', $this->string());
    }
}
