<?php

use yii\db\Migration;

class m161115_204105_add_change_order_status_rule_to_rbac extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        $changeOrderStatus = $auth->createPermission('changeOrderStatus');
        $changeOrderStatus->description = 'Change order status';
        $auth->add($changeOrderStatus);

        $orderManager = $auth->getRole('orderManager');
        $auth->addChild($orderManager, $changeOrderStatus);
    }

    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $orderManager = $auth->getRole('orderManager');
        $changeOrderStatus = $auth->getPermission('changeOrderStatus');

        $auth->removeChild($orderManager, $changeOrderStatus);
        $auth->remove($changeOrderStatus);
    }

}
