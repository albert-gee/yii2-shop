<?php

use yii\db\Migration;

/**
 * Handles adding mail to table `shop_order_status`.
 */
class m161116_123601_add_mail_column_to_shop_order_status_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('shop_order_status_translation', 'mail', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('shop_order_status_translation', 'mail');
    }
}
