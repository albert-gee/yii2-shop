<?php
namespace xalberteinsteinx\shop\widgets;

use xalberteinsteinx\shop\common\entities\DeliveryMethod;
use Yii;
use yii\base\Widget;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget adds delivery methods to shop order page
 *
 * Example:
 * <?= Delivery::widget([
 * ]); ?>
 *
 */
class Delivery extends Widget
{
    public $form;
    public $model;
    public $address;

    public function init()
    {
    }

    public function run()
    {
        $deliveryMethods = DeliveryMethod::find()->all();
        return $this->render('delivery', [
            'address' => $this->address,
            'deliveryMethods' => $deliveryMethods,
            'form' => $this->form,
            'model' => $this->model,
        ]);
    }

}