<?php

use yii\db\Migration;

class m160831_102349_create_table_partner_request extends Migration
{
    public function up()
    {
        $this->createTable('partner_request', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer(),
            'company_name' => $this->string(),
            'website' => $this->string(),
            'message' => $this->text(),
            'created_at' => $this->dateTime(),
            'moderation_status' => $this->integer(2)->defaultValue(1),
            'moderated_by' => $this->integer(),
            'moderated_at' => $this->dateTime()
        ]);
        $this->addForeignKey('user_partner_request_sender', 'partner_request', 'sender_id', 'user', 'id', 'cascade', 'cascade');
        $this->addForeignKey('user_partner_request_moderated_by', 'partner_request', 'moderated_by', 'user', 'id', 'cascade', 'cascade');

        /*Creating RBAC permission*/
        $auth = Yii::$app->authManager;

        $moderatePartnerRequest = $auth->createPermission('moderatePartnerRequest');
        $moderatePartnerRequest->description = 'Moderate partner request';

        $auth->add($moderatePartnerRequest);

        $moderationManager = $auth->getRole('moderationManager');
        $auth->addChild($moderationManager, $moderatePartnerRequest);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $moderationManager = $auth->getRole('moderationManager');
        $moderatePartnerRequest = $auth->getPermission('moderatePartnerRequest');

        Yii::$app->authManager->removeChild($moderationManager, $moderatePartnerRequest);
        $auth->remove($moderatePartnerRequest);

        $this->dropForeignKey('user_partner_request_moderated_by', 'partner_request');
        $this->dropForeignKey('user_partner_request_sender', 'partner_request');

        $this->dropTable('partner_request');


    }
}
