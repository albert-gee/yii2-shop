<?php

use yii\db\Migration;

/**
 * Handles adding description to table `shop_vendor`.
 */
class m170116_115155_add_description_column_to_shop_vendor_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_vendor', 'description', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_vendor', 'description');
    }
}
