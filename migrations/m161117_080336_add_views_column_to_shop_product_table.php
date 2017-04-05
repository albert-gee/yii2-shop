<?php

use yii\db\Migration;

/**
 * Handles adding views to table `shop_product`.
 */
class m161117_080336_add_views_column_to_shop_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_product', 'views', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_product', 'views');
    }
}
