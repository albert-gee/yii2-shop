<?php
use yii\db\Migration;
class m161125_113353_add_roles_and_permissions extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;
        $viewPaymentMethodList = $auth->createPermission('viewPaymentMethodList');
        $viewPaymentMethodList->description = 'View payment method list';
        $auth->add($viewPaymentMethodList);
        $savePaymentMethod = $auth->createPermission('savePaymentMethod');
        $savePaymentMethod->description = 'Save payment method';
        $auth->add($savePaymentMethod);
        $deletePaymentMethod = $auth->createPermission('deletePaymentMethod');
        $deletePaymentMethod->description = 'Delete payment method';
        $auth->add($deletePaymentMethod);
        $paymentManager = $auth->createRole('paymentManager');
        $paymentManager->description = 'Payment manager';
        $auth->add($paymentManager);
        $auth->addChild($paymentManager, $viewPaymentMethodList);
        $auth->addChild($paymentManager, $savePaymentMethod);
        $auth->addChild($paymentManager, $deletePaymentMethod);
    }
    public function down()
    {
        $auth = Yii::$app->authManager;
        $viewPaymentMethodList = $auth->getPermission('viewPaymentMethodList');
        $savePaymentMethod = $auth->getPermission('savePaymentMethod');
        $deletePaymentMethod = $auth->getPermission('deletePaymentMethod');
        $paymentManager = $auth->getRole('paymentManager');
        $auth->removeChild($paymentManager, $viewPaymentMethodList);
        $auth->removeChild($paymentManager, $savePaymentMethod);
        $auth->removeChild($paymentManager, $deletePaymentMethod);
        $auth->remove($viewPaymentMethodList);
        $auth->remove($savePaymentMethod);
        $auth->remove($deletePaymentMethod);
        $auth->remove($paymentManager);
    }
}