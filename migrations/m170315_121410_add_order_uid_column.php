<?php

use yii\db\Migration;

class m170315_121410_add_order_uid_column extends Migration
{
    public function up()
    {
        $this->addColumn('shop_order', 'uid', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('shop_order', 'uid');
    }
}
