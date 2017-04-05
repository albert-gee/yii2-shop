<?php

use yii\db\Migration;

class m160902_102946_add_access_admin_panel_permission extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $accessAdminPanel = $auth->createPermission('accessAdminPanel');
        $accessAdminPanel->description = 'Access admin panel';
        $auth->add($accessAdminPanel);


        $productManager = $auth->getRole('productManager');
        $productPartner = $auth->getRole('productPartner');

        $auth->addChild($productManager, $accessAdminPanel);
        $auth->addChild($productPartner, $accessAdminPanel);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $productManager = $auth->getRole('productManager');
        $productPartner = $auth->getRole('productPartner');
        $accessAdminPanel = $auth->getPermission('accessAdminPanel');

        $auth->removeChild($productPartner, $accessAdminPanel);
        $auth->removeChild($productManager, $accessAdminPanel);
        $auth->remove($accessAdminPanel);
    }
}
