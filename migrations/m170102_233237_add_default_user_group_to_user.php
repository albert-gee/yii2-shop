<?php

use yii\db\Migration;

class m170102_233237_add_default_user_group_to_user extends Migration
{
    public function up()
    {
        $this->alterColumn('user', 'user_group_id', $this->integer()
            ->defaultValue(\albertgeeca\shop\common\components\user\models\UserGroup::USER_GROUP_ALL_USERS));

        $users = \albertgeeca\shop\common\components\user\models\User::find()->all();
        foreach ($users as $user) {
            $user->user_group_id = \albertgeeca\shop\common\components\user\models\UserGroup::USER_GROUP_ALL_USERS;
            if ($user->validate()) $user->save();
        }
    }

    public function down()
    {
        $this->alterColumn('user', 'user_group_id', $this->integer()->defaultValue(false));
    }

}
