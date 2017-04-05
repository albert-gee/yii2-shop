<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `fk_from_shop_order_product`.
 */
class m170328_005032_drop_fk_from_shop_order_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropForeignKey('combination_id:shop_product_combination_id', 'shop_order_product');
        $this->addForeignKey('combination_id:shop_combination_id',
            'shop_order_product', 'combination_id', 'shop_combination', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addForeignKey('combination_id:shop_product_combination_id',
            'shop_order_product', 'combination_id', 'shop_product_combination', 'id');
        $this->dropForeignKey('combination_id:shop_product_combination_id', 'shop_order_product');
    }
}
