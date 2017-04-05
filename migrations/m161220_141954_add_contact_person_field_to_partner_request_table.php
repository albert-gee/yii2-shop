<?php

use yii\db\Migration;

class m161220_141954_add_contact_person_field_to_partner_request_table extends Migration
{
    public function up()
    {
        $this->addColumn('partner_request', 'contact_person', $this->string());
    }

    public function down()
    {
        $this->dropColumn('partner_request', 'contact_person');
    }
}
