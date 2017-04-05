<?php

use yii\db\Migration;

class m170403_155923_add_info_field_to_profile_table extends Migration
{
    public function up()
    {
        $this->addColumn('profile', 'info', $this->string(120));
    }

    public function down()
    {
        $this->dropColumn('profile', 'info');
    }
}
