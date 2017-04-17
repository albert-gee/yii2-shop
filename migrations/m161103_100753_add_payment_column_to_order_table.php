<?php
use yii\db\Migration;
/**
 * Handles adding payment to table `order`.
 */
class m161103_100753_add_payment_column_to_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_order', 'payment_method_id', $this->integer());
        $this->addForeignKey('shop_order_payment_method_id:payment_method_id',
            'shop_order', 'payment_method_id',
            'payment_method', 'id');
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('shop_order_payment_method_id:payment_method_id',
            'shop_order');
        $this->dropColumn('shop_order', 'payment_method_id');
    }
}