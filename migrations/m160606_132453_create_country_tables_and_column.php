<?php

use yii\db\Migration;

/**
 * Handles the creation for table `country_tables_and_column`.
 */
class m160606_132453_create_country_tables_and_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shop_product_country', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('shop_product_country_translation', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer(),
            'language_id' => $this->integer(),
            'title' => $this->string()
        ]);

        $this->addColumn('shop_product', 'country_id', 'integer');

        $this->addForeignKey('shop_product_country:country_id', 'shop_product_country_translation', 'country_id', 'shop_product_country', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product_country_translation:language_id', 'shop_product_country_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_product:id', 'shop_product', 'country_id', 'shop_product_country', 'id', 'cascade', 'cascade');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        $this->dropForeignKey('shop_product:id', 'shop_product');
        $this->dropColumn('shop_product', 'country_id');
        $this->dropForeignKey('shop_product_country_translation:language_id', 'shop_product_country_translation');
        $this->dropForeignKey('shop_product_country:country_id', 'shop_product_country_translation');

        $this->dropTable('shop_product_country_translation');
        $this->dropTable('shop_product_country');
    }
}
