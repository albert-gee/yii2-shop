<?php

use yii\db\Migration;

class m160530_085535_return_full_text_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('shop_product_translation', 'full_text', 'text');
    }

    public function safeDown()
    {
        $this->dropColumn('shop_product_translation', 'full_text');
    }
}
