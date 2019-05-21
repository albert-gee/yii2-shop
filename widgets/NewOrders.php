<?php
namespace albertgeeca\shop\widgets;

use albertgeeca\shop\common\entities\Order;
use albertgeeca\shop\common\entities\OrderStatus;
use yii\base\Widget;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget shows last orders.
 *
 * Example:
 * <?= NewOrders::widget([
 * 'num' =>10
 * ]); ?>
 *
 */
class NewOrders extends Widget
{
    /**
     * @var integer
     * Number of orders which will be shown.
     */
    public $num = 10;

    public function run()
    {
        $orders = Order::find()->where(['status' => OrderStatus::STATUS_CONFIRMED])->orderBy('id DESC')->limit($this->num)->all();

        return $this->render('new-orders', [
            'orders' => $orders,
        ]);
    }

}