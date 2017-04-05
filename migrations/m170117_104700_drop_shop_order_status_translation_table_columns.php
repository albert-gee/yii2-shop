<?php

use yii\db\Migration;

class m170117_104700_drop_shop_order_status_translation_table_columns extends Migration
{
    public function up()
    {
        $this->dropColumn('shop_order_status_translation', 'mail');
        $this->addColumn('shop_order_status', 'mail_id', $this->integer());
        $this->addForeignKey('mail_id:email_template_id',
            'shop_order_status', 'mail_id', 'email_template', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('mail_id:email_template_id', 'shop_order_status');

        $this->dropColumn('shop_order_status', 'mail_id');
        $this->addColumn('shop_order_status_translation', 'mail', $this->string());
    }
}
