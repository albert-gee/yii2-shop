<?php

use yii\db\Migration;

/**
 * Handles the creation for table `shop_product_sale_type_table`.
 */
class m160524_143815_create_shop_product_sale_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('shop_product_sale_type', [
            'id' => $this->primaryKey(),
            'title' => $this->string()
        ]);

        $this->insert('shop_product_sale_type', [
            'id' => 1,
            'title' => 'money',
        ]);

        $this->insert('shop_product_sale_type', [
            'id' => 2,
            'title' => 'percent',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('shop_product_sale_type');
    }
}
