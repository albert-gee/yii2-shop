<?php

use yii\db\Migration;

class m161216_113453_change_null_parent_id_for_category_to_zero extends Migration
{
    public function up()
    {
        $this->update('shop_category', ['parent_id' => 0], [
            'parent_id' => null
        ]);
    }

    public function down()
    {
        $this->update('shop_category', ['parent_id' => null], [
            'parent_id' => 0
        ]);
    }
}
