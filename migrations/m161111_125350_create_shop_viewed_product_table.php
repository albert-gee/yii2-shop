<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_viewd_product`.
 */
class m161111_125350_create_shop_viewed_product_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shop_viewed_product', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'user_id' => $this->integer(),
            'creation_time' => $this->dateTime(),
            'update_time' => $this->dateTime()
        ]);

        $this->addForeignKey('shop_viewed_product_product_id:shop_product_id',
            'shop_viewed_product', 'product_id', 'shop_product', 'id', 'cascade', 'cascade');
        $this->addForeignKey('shop_viewed_product_user_id:user_id',
            'shop_viewed_product', 'user_id', 'user', 'id', 'cascade', 'cascade');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('shop_viewed_product_product_id:shop_product_id',
            'shop_viewed_product');
        $this->dropForeignKey('shop_viewed_product_user_id:user_id',
            'shop_viewed_product');

        $this->dropTable('shop_viewed_product');
    }
}
