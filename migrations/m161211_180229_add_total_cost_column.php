<?php

use yii\db\Migration;

class m161211_180229_add_total_cost_column extends Migration
{
    public function up()
    {
        $this->addColumn('shop_order', 'total_cost', $this->float());
    }

    public function down()
    {
        $this->dropColumn('shop_order', 'total_cost');
    }

}
