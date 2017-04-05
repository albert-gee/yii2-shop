<?php

use yii\db\Migration;

class m161016_200044_add_post_office_column extends Migration
{
    public function up()
    {
        $this->addColumn('user_address', 'postoffice', $this->integer(6));
    }

    public function down()
    {
        $this->dropColumn('user_address', 'postoffice');
    }
}
