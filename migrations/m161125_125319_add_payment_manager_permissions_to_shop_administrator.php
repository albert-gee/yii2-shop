<?php
use yii\db\Migration;
class m161125_125319_add_payment_manager_permissions_to_shop_administrator extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;
        $paymentManager = $auth->getRole('paymentManager');
        $shopAdministrator = $auth->getRole('shopAdministrator');
        $auth->addChild($shopAdministrator, $paymentManager);
    }
    public function down()
    {
        $auth = Yii::$app->authManager;
        $paymentManager = $auth->getRole('paymentManager');
        $shopAdministrator = $auth->getRole('shopAdministrator');
        $auth->removeChild($shopAdministrator, $paymentManager);
    }
}