<?php

use yii\db\Migration;

class m161025_153835_create_shop_admin_role extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $attributeManager = $auth->getRole('attributeManager');
        $countryManager = $auth->getRole('countryManager');
        $currencyManager = $auth->getRole('currencyManager');
        $deliveryMethodManager = $auth->getRole('deliveryMethodManager');
        $orderManager = $auth->getRole('orderManager');
        $orderStatusManager = $auth->getRole('orderStatusManager');
        $productAvailabilityManager = $auth->getRole('productAvailabilityManager');
        $shopCategoryManager = $auth->getRole('shopCategoryManager');
        $productManager = $auth->getRole('productManager');

        $shopAdministrator = $auth->createRole('shopAdministrator');
        $shopAdministrator->description = 'Shop administrator';
        $auth->add($shopAdministrator);

        $auth->addChild($shopAdministrator, $attributeManager);
        $auth->addChild($shopAdministrator, $countryManager);
        $auth->addChild($shopAdministrator, $currencyManager);
        $auth->addChild($shopAdministrator, $deliveryMethodManager);
        $auth->addChild($shopAdministrator, $orderManager);
        $auth->addChild($shopAdministrator, $orderStatusManager);
        $auth->addChild($shopAdministrator, $productAvailabilityManager);
        $auth->addChild($shopAdministrator, $shopCategoryManager);
        $auth->addChild($shopAdministrator, $productManager);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $shopAdministrator = $auth->getRole('shopAdministrator');
        $auth->removeChildren($shopAdministrator);
        $auth->remove($shopAdministrator);
    }

}