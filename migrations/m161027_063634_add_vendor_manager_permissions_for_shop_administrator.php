<?php

use yii\db\Migration;

class m161027_063634_add_vendor_manager_permissions_for_shop_administrator extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $shopAdmin = $auth->getRole('shopAdministrator');
        $vendorManager = $auth->getRole('vendorManager');

        if (!$auth->hasChild($shopAdmin, $vendorManager)) {
            $auth->addChild($shopAdmin, $vendorManager);
        };

        $accessAdminPanel = $auth->getPermission('accessAdminPanel');

        $productManager = $auth->getRole('productManager');
        if ($auth->hasChild($productManager, $accessAdminPanel)) {
            $auth->removeChild($productManager, $accessAdminPanel);
        };

        $productPartner = $auth->getRole('productPartner');
        if ($auth->hasChild($productPartner, $accessAdminPanel)) {
            $auth->removeChild($productPartner, $accessAdminPanel);
        };
        $shopAdministrator = $auth->getRole('shopAdministrator');
        if ($auth->hasChild($shopAdministrator, $accessAdminPanel)) {
            $auth->removeChild($shopAdministrator, $accessAdminPanel);
        };
    }

    public function down()
    {
        $auth = Yii::$app->authManager;
        $shopAdmin = $auth->getRole('shopAdministrator');
        $vendorManager = $auth->getRole('vendorManager');

        $auth->removeChild($shopAdmin, $vendorManager);

        $accessAdminPanel = $auth->getPermission('accessAdminPanel');

        $productManager = $auth->getRole('productManager');
        if (!$auth->hasChild($productManager, $accessAdminPanel)) {
            $auth->addChild($productManager, $accessAdminPanel);
        };
        $productPartner = $auth->getRole('productPartner');
        if ($auth->hasChild($productPartner, $accessAdminPanel)) {
            $auth->addChild($productPartner, $accessAdminPanel);
        };
        $shopAdministrator = $auth->getRole('shopAdministrator');
        if ($auth->hasChild($shopAdministrator, $accessAdminPanel)) {
            $auth->addChild($shopAdministrator, $accessAdminPanel);
        };
    }
}
