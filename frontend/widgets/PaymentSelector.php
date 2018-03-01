<?php
namespace sointula\shop\frontend\widgets;

use sointula\shop\common\entities\Order;
use sointula\shop\common\entities\PaymentMethod;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Widget;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget adds payment select.
 *
 * Example:
 * <?= PaymentSelector::widget([
 * ]); ?>
 *
 */
class PaymentSelector extends Widget
{
    /**
     * @var ActiveForm
     */
    public $form;
    /**
     * @var Order
     */
    public $order;
    public function run()
    {
        $paymentMethods = PaymentMethod::find()->all();
        return $this->render('payment-selector', [
            'order' => $this->order,
            'form' => $this->form,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}