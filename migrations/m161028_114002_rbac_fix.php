<?php

use yii\db\Migration;

class m161028_114002_rbac_fix extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        $updateProduct = $auth->getPermission('updateProduct');
        $updateOwnProduct = $auth->getPermission('updateOwnProduct');
        $deleteProduct = $auth->getPermission('deleteProduct');
        $deleteOwnProduct = $auth->getPermission('deleteOwnProduct');

        $auth->removeChild($updateProduct, $updateOwnProduct);
        $auth->removeChild($deleteProduct, $deleteOwnProduct);


        $auth->addChild($updateOwnProduct, $updateProduct);
        $auth->addChild($deleteOwnProduct, $deleteProduct);

    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $updateProduct = $auth->getPermission('updateProduct');
        $updateOwnProduct = $auth->getPermission('updateOwnProduct');
        $deleteProduct = $auth->getPermission('deleteProduct');
        $deleteOwnProduct = $auth->getPermission('deleteOwnProduct');

        $auth->removeChild($updateOwnProduct, $updateProduct);
        $auth->removeChild($deleteOwnProduct, $deleteProduct);

        $auth->addChild($updateProduct, $updateOwnProduct);
        $auth->addChild($deleteProduct, $deleteOwnProduct);
    }


}
