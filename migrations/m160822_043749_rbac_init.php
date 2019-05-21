<?php

use albertgeeca\shop\common\components\rbac\ProductOwnerRule;
use yii\db\Migration;

class m160822_043749_rbac_init extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        /*Add permissions*/
        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'Create product';
        $auth->add($createProduct);

        $updateProduct = $auth->createPermission('updateProduct');
        $updateProduct->description = 'Update product';
        $auth->add($updateProduct);

        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Delete product';
        $auth->add($deleteProduct);


        $createProductWithoutModeration = $auth->createPermission('createProductWithoutModeration');
        $createProductWithoutModeration->description = 'Create product without moderation';
        $auth->add($createProductWithoutModeration);


        /*Add the rule*/
        $rule = new ProductOwnerRule;
        $auth->add($rule);

        $updateOwnProduct = $auth->createPermission('updateOwnProduct');
        $updateOwnProduct->description = 'Update own product';
        $updateOwnProduct->ruleName = $rule->name;
        $auth->add($updateOwnProduct);

        $deleteOwnProduct = $auth->createPermission('deleteOwnProduct');
        $deleteOwnProduct->description = 'Delete own product';
        $deleteOwnProduct->ruleName = $rule->name;
        $auth->add($deleteOwnProduct);


        /*Add roles*/
        $productPartner = $auth->createRole('productPartner');
        $productPartner->description = 'Product Partner';
        $auth->add($productPartner);

        $productManager = $auth->createRole('productManager');
        $productManager->description = 'Product Manager';
        $auth->add($productManager);


        $auth->addChild($updateProduct, $updateOwnProduct);
        $auth->addChild($deleteProduct, $deleteOwnProduct);


        $auth->addChild($productPartner, $createProduct);
        $auth->addChild($productPartner, $updateOwnProduct);
        $auth->addChild($productPartner, $deleteOwnProduct);

        $auth->addChild($productManager, $updateProduct);
        $auth->addChild($productManager, $deleteProduct);

        $auth->addChild($createProductWithoutModeration, $createProduct);

        $auth->addChild($productManager, $createProductWithoutModeration);
        $auth->addChild($productManager, $productPartner);

    }

    public function down()
    {
        Yii::$app->authManager->removeAll();

    }
}
