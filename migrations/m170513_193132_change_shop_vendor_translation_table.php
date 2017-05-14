<?php

use yii\db\Migration;

class m170513_193132_change_shop_vendor_translation_table extends Migration
{
    public function up()
    {
        $this->alterColumn('shop_vendor_translation', 'description', $this->text());
    }

    public function down()
    {
        $this->alterColumn('shop_vendor_translation', 'description', $this->string());

    }
}
