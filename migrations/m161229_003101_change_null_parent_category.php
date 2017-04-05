<?php

use yii\db\Migration;

class m161229_003101_change_null_parent_category extends Migration
{
    public function up()
    {
        $this->update('shop_category', ['parent_id' => null], [
            'parent_id' => 0
        ]);
    }

    public function down()
    {
        $this->update('shop_category', ['parent_id' => 0], [
            'parent_id' => null
        ]);
    }
}
