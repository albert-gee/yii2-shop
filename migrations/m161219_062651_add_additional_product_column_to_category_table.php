<?php

use yii\db\Migration;

/**
 * Handles adding additional_product to table `category`.
 */
class m161219_062651_add_additional_product_column_to_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_category', 'additional_products', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_category', 'additional_products');
    }
}
