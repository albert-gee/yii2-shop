<?php

use yii\db\Migration;

class m160712_122938_add_show_column_to_shop_category extends Migration
{
    public function up()
    {
        $this->addColumn('shop_category', 'show', 'boolean');
    }

    public function down()
    {
        $this->dropColumn('shop_category', 'show');
    }
    
}
