<?php

use yii\db\Migration;

/**
 * Handles adding delivery to table `order`.
 */
class m170309_022700_add_delivery_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_order', 'delivery_id', $this->integer());
        $this->addColumn('shop_order', 'delivery_post_office', $this->string());

        $this->addForeignKey('shop_order_delivery_id:shop_delivery_id',
            'shop_order', 'delivery_id', 'shop_delivery_method', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('shop_order_delivery_id:shop_delivery_id',
            'shop_order');
        $this->dropColumn('shop_order', 'delivery_id');
        $this->dropColumn('shop_order', 'delivery_post_office');
    }
}
