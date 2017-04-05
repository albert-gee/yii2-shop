<?php

use yii\db\Migration;

class m161226_003706_add_user_group_table extends Migration
{
    public function up()
    {
        $this->createTable('user_group', [
            'id' => $this->primaryKey(),
        ]);
        $this->createTable('user_group_translation', [
            'id' => $this->primaryKey(),
            'user_group_id' => $this->integer(),
            'language_id' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->string()
        ]);

        $this->addForeignKey('user_group_translation:user_group_id',
            'user_group_translation', 'user_group_id', 'user_group', 'id', 'cascade', 'cascade');
        $this->addForeignKey('user_group_translation:language_id',
            'user_group_translation', 'language_id', 'language', 'id', 'cascade', 'cascade');

        $this->addColumn('user', 'user_group_id', $this->integer());
        $this->addForeignKey('user:user_group_id', 'user', 'user_group_id', 'user_group', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('user:user_group_id', 'user');
        $this->dropColumn('user', 'user_group_id');

        $this->dropForeignKey('user_group_translation:language_id',
            'user_group_translation');
        $this->dropForeignKey('user_group_translation:user_group_id',
            'user_group_translation');

        $this->dropTable('user_group_translation');
        $this->dropTable('user_group');
    }
}
