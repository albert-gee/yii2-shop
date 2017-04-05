<?php

use yii\db\Migration;

class m170103_042623_add_column_discount_to_shop_product_table extends Migration
{
    public function up()
    {
        $this->addColumn('shop_product', 'discount', $this->float()->after('price'));
        $this->addColumn('shop_product', 'discount_type_id', $this->integer()->after('discount'));

        $this->addForeignKey('discount_type_id:shop_price_discount_type_id',
            'shop_product', 'discount_type_id', 'shop_price_discount_type', 'id');

    }

    public function down()
    {
        $this->dropForeignKey('discount_type_id:shop_price_discount_type_id',
            'shop_product');
        $this->dropColumn('shop_product', 'discount_type_id');
        $this->dropColumn('shop_product', 'discount');
    }
}
