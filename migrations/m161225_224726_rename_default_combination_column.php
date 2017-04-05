<?php

use yii\db\Migration;

class m161225_224726_rename_default_combination_column extends Migration
{
    public function up()
    {
        $this->renameColumn('shop_product_combination', 'default', 'default_combination');
    }

    public function down()
    {
        $this->renameColumn('shop_product_combination', 'default_combination', 'default');

    }

}
