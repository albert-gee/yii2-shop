<?php

use yii\db\Migration;

class m170402_124643_change_phone_column_in_profile_table extends Migration
{
    public function up()
    {
        $this->alterColumn('profile', 'phone', $this->string(25));
    }

    public function down()
    {
        $this->alterColumn('profile', 'phone', $this->string(16));

    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
