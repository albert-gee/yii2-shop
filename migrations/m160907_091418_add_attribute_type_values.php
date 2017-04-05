<?php

use bl\multilang\entities\Language;
use yii\db\Migration;

class m160907_091418_add_attribute_type_values extends Migration
{
    public function up()
    {
        /*INSERT TYPE CONSTANTS*/
        $this->insert('shop_attribute_type', [
            'id' => 1,
            'title' => 'Drop down list'
        ]);
        $this->insert('shop_attribute_type', [
            'id' => 2,
            'title' => 'Radio button'
        ]);
        $this->insert('shop_attribute_type', [
            'id' => 3,
            'title' => 'Color'
        ]);
        $this->insert('shop_attribute_type', [
            'id' => 4,
            'title' => 'Texture'
        ]);

    }

    public function down()
    {
        $this->delete('shop_attribute_type', [
            'id' => 1
        ]);
        $this->delete('shop_attribute_type', [
            'id' => 2
        ]);
        $this->delete('shop_attribute_type', [
            'id' => 3
        ]);
        $this->delete('shop_attribute_type', [
            'id' => 4
        ]);
    }

}
