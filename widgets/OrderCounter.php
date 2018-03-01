<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

namespace sointula\shop\widgets;

use sointula\shop\common\entities\Order;
use sointula\shop\common\entities\OrderStatus;
use yii\base\Widget;

class OrderCounter extends Widget
{

    public function init()
    {
    }

    public function run()
    {
        $count = Order::find()->where(['status' => OrderStatus::STATUS_CONFIRMED])->count();
        return $this->render('order-counter', [
            'count' => $count
        ]);
    }
}