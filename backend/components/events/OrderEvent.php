<?php
namespace xalberteinsteinx\shop\backend\components\events;

use xalberteinsteinx\shop\models\Order;
use yii\base\Event;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class OrderEvent extends Event
{
    /**
     * @var Order
     */
    public $model;

}