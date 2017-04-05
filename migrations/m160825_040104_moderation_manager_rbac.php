<?php

use yii\db\Migration;

class m160825_040104_moderation_manager_rbac extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $moderateProductCreation = $auth->createPermission('moderateProductCreation');
        $moderateProductCreation->description = 'Moderate Product Creation';
        $auth->add($moderateProductCreation);


        $moderationManager = $auth->createRole('moderationManager');
        $moderationManager->description = 'Moderation Manager';
        $auth->add($moderationManager);

        $auth->addChild($moderationManager, $moderateProductCreation);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $moderationManager = $auth->getRole('moderationManager');
        $moderateProductCreation = $auth->getPermission('moderateProductCreation');

        Yii::$app->authManager->removeChild($moderationManager, $moderateProductCreation);

        Yii::$app->authManager->remove($moderateProductCreation);
        Yii::$app->authManager->remove($moderationManager);

    }
}
