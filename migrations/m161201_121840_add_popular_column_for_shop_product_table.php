<?php

use yii\db\Migration;

class m161201_121840_add_popular_column_for_shop_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'popular', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('shop_product', 'popular');
    }

}
