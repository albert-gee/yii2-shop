<?php

use sointula\shop\common\entities\Category;
use yii\db\Migration;

class m170209_150220_add_additional_product_value_for_shop_category extends Migration
{
    public function up()
    {
        $categories = \sointula\shop\common\entities\Category::find()->all();

        /**
         * @var $category Category
         */
        foreach ($categories as $category) {
            if (is_null($category->additional_products)) {
                $category->additional_products = false;
                if ($category->validate()) $category->save();
            }
        }
    }

    public function down()
    {
        echo "m170209_150220_add_additional_product_value_for_shop_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
