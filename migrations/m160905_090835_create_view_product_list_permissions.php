<?php

use yii\db\Migration;

class m160905_090835_create_view_product_list_permissions extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;
        $viewProductList = $auth->createPermission('viewProductList');
        $viewProductList->description = ('View product list');
        $viewCompleteProductList = $auth->createPermission('viewCompleteProductList');
        $viewCompleteProductList->description = ('View complete product list');

        $auth->add($viewProductList);
        $auth->add($viewCompleteProductList);

        $productPartner = $auth->getRole('productPartner');
        $productManager = $auth->getRole('productManager');
        $moderationManager = $auth->getRole('moderationManager');

        $auth->addChild($productPartner, $viewProductList);
        $auth->addChild($productManager, $viewProductList);
        $auth->addChild($moderationManager, $viewProductList);

        $auth->addChild($productManager, $viewCompleteProductList);
        $auth->addChild($moderationManager, $viewCompleteProductList);

    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $productPartner = $auth->getRole('productPartner');
        $productManager = $auth->getRole('productManager');
        $moderationManager = $auth->getRole('moderationManager');

        $viewProductList = $auth->getPermission('viewProductList');
        $viewCompleteProductList = $auth->getPermission('viewCompleteProductList');

        $auth->removeChild($productManager, $viewCompleteProductList);
        $auth->removeChild($moderationManager, $viewCompleteProductList);

        $auth->removeChild($productPartner, $viewProductList);
        $auth->removeChild($productManager, $viewProductList);
        $auth->removeChild($moderationManager, $viewProductList);

        $auth->remove($viewProductList);
        $auth->remove($viewCompleteProductList);

    }
}
