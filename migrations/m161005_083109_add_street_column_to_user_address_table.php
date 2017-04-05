<?php

use yii\db\Migration;

/**
 * Handles adding street to table `user_address`.
 */
class m161005_083109_add_street_column_to_user_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user_address', 'street', $this->string()->after('city'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user_address', 'street');
    }
}
