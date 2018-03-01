<?php

use yii\db\Migration;

class m161230_091442_insert_default_user_id extends Migration
{
    public function up()
    {
        $this->insert('user_group', [
            'id' => \sointula\shop\common\components\user\models\UserGroup::USER_GROUP_ALL_USERS
        ]);
        $this->insert('user_group_translation', [
            'user_group_id' => \sointula\shop\common\components\user\models\UserGroup::USER_GROUP_ALL_USERS,
            'language_id' => \bl\multilang\entities\Language::getDefault()->id,
            'title' => 'All users'
        ]);
    }

    public function down()
    {
        $this->delete('user_group', [
            'id' => \sointula\shop\common\components\user\models\UserGroup::USER_GROUP_ALL_USERS
        ]);
        $this->delete('user_group_translation', [
            'user_group_id' => \sointula\shop\common\components\user\models\UserGroup::USER_GROUP_ALL_USERS,
            'language_id' => \bl\multilang\entities\Language::getDefault()->id,
        ]);
    }

}
