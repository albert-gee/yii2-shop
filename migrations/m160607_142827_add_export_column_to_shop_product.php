<?php

use yii\db\Migration;

/**
 * Handles adding export_column to table `shop_product`.
 */
class m160607_142827_add_export_column_to_shop_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_product', 'export', 'boolean');
        $this->execute('ALTER TABLE shop_product ALTER COLUMN `export` SET DEFAULT "1"');
        $this->execute('UPDATE shop_product SET export = "1"');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_product', 'export');
    }
}
