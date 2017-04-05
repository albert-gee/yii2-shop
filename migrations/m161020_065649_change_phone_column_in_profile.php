<?php

use yii\db\Migration;

class m161020_065649_change_phone_column_in_profile extends Migration
{
    public function up()
    {
        $this->dropColumn('profile', 'phone');
        $this->addColumn('profile', 'phone', $this->string(16));
    }

    public function down()
    {
        $this->dropColumn('profile', 'phone');
        $this->addColumn('profile', 'phone', $this->integer(16));
    }

}
