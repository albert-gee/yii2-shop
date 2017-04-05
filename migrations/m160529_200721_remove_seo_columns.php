<?php

use yii\db\Migration;

class m160529_200721_remove_seo_columns extends Migration
{

    public function safeUp()
    {
        $this->dropColumn('shop_product_translation', 'seoUrl');
        $this->dropColumn('shop_product_translation', 'seoTitle');
        $this->dropColumn('shop_product_translation', 'seoDescription');
        $this->dropColumn('shop_product_translation', 'seoKeywords');
        $this->dropColumn('shop_product_translation', 'full_text');

        $this->dropColumn('shop_category_translation', 'seoUrl');
        $this->dropColumn('shop_category_translation', 'seoTitle');
        $this->dropColumn('shop_category_translation', 'seoDescription');
        $this->dropColumn('shop_category_translation', 'seoKeywords');
    }

    public function safeDown()
    {
        $this->addColumn('shop_product_translation', 'seoUrl', $this->string());
        $this->addColumn('shop_product_translation', 'seoTitle', $this->string());
        $this->addColumn('shop_product_translation', 'seoDescription', $this->string());
        $this->addColumn('shop_product_translation', 'seoKeywords', $this->string());
        $this->addColumn('shop_product_translation', 'full_text', $this->string());

        $this->addColumn('shop_category_translation', 'seoUrl', $this->string());
        $this->addColumn('shop_category_translation', 'seoTitle', $this->string());
        $this->addColumn('shop_category_translation', 'seoDescription', $this->string());
        $this->addColumn('shop_category_translation', 'seoKeywords', $this->string());
        $this->addColumn('shop_category_translation', 'full_text', $this->string());
    }
}
