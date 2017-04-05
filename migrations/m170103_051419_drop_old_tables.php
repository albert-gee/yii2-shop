<?php

use yii\db\Migration;

class m170103_051419_drop_old_tables extends Migration
{
    public function up()
    {
        $this->dropForeignKey('shop_product_combination_attribute:shop_product_combination_id', 'shop_product_combination_attribute');
        $this->dropForeignKey('shop_product_combination_attribute:shop_attribute_id', 'shop_product_combination_attribute');
        $this->dropForeignKey('shop_product_combination_attribute:shop_attribute_value_id', 'shop_product_combination_attribute');

        $this->dropTable('shop_product_combination_attribute');

        $this->dropForeignKey('shop_product_combination_image:shop_product_combination_id', 'shop_product_combination_image');
        $this->dropForeignKey('shop_product_combination_image:shop_product_image_id', 'shop_product_combination_image');

        $this->dropTable('shop_product_combination_image');

        $this->dropForeignKey('shop_product_price:product_id', 'shop_product_price');
        $this->dropForeignKey('shop_product_price:sale_type_id', 'shop_product_price');
        $this->dropForeignKey('shop_product_price:user_group_id', 'shop_product_price');

        $this->dropForeignKey('shop_product_combination:shop_product_id', 'shop_product_combination');
        $this->dropForeignKey('shop_product_combination:shop_product_sale_type_id', 'shop_product_combination');
        $this->dropForeignKey('shop_product_combination:user_group_id', 'shop_product_combination');

        $this->dropTable('shop_product_combination');

        $this->dropForeignKey('shop_product_price_translation:price_id', 'shop_product_price_translation');
        $this->dropForeignKey('shop_product_price_translation:language_id', 'shop_product_price_translation');

        $this->dropTable('shop_product_price_translation');
        $this->dropTable('shop_product_sale_type');
        $this->dropTable('shop_product_price');




    }

    public function down()
    {
        return true;
    }
}
