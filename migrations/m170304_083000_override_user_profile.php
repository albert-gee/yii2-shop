<?php

use dektrium\user\migrations\Migration as UserMigration;
use yii\db\Schema;

class m170304_083000_override_user_profile extends UserMigration
{
    public function up()
    {
        $this->createTable('user_address', [
            'id' => $this->primaryKey(11),
            'user_profile_id' => $this->integer(11),
            'country' => $this->string(255),
            'region' => $this->string(255),
            'city' => $this->string(255),
            'house' => $this->string(255),
            'apartment' => $this->string(11),
            'zipcode' => $this->integer(11),
        ]);

        $this->dropForeignKey('fk_user_profile', 'profile');

        $this->dropTable('profile');
        $this->createTable('profile', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer(11),
            'name' => $this->string(255),
            'surname' => $this->string(255),
            'patronymic' => $this->string(255),
            'avatar' => $this->string(255),
            'phone' => $this->integer(16),
        ]);

        $this->addForeignKey('profile_user_id:user_id', 'profile', 'user_id', 'user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('user_address_user_profile_id:profile_id', 'user_address', 'user_profile_id', 'profile', 'id', 'cascade', 'cascade');
        return true;
    }

    public function down()
    {

        $this->dropForeignKey('user_address_user_profile_id:profile_id', 'user_address');
        $this->dropForeignKey('profile_user_id:user_id', 'profile');
        $this->dropTable('profile');
        $this->dropTable('user_address');

        $this->createTable('profile', [
            'user_id'        => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'name'           => Schema::TYPE_STRING . '(255)',
            'public_email'   => Schema::TYPE_STRING . '(255)',
            'gravatar_email' => Schema::TYPE_STRING . '(255)',
            'gravatar_id'    => Schema::TYPE_STRING . '(32)',
            'location'       => Schema::TYPE_STRING . '(255)',
            'website'        => Schema::TYPE_STRING . '(255)',
            'bio'            => Schema::TYPE_TEXT,
        ], $this->tableOptions);

        $this->addForeignKey('fk_user_profile', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }
}
