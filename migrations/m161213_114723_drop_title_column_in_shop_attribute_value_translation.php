<?php

use yii\db\Migration;

class m161213_114723_drop_title_column_in_shop_attribute_value_translation extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('shop_attribute_value_translation', 'title');
    }

    public function safeDown()
    {
        $this->addColumn('shop_attribute_value_translation', 'title', $this->string());
    }

}
