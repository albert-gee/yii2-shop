<?php
namespace albertgeeca\shop\backend\components\events;

use albertgeeca\shop\common\entities\ProductCountry;
use albertgeeca\shop\common\entities\Vendor;
use yii\base\Event;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class VendorEvent extends Event
{

    /**
     * @var Vendor
     */
    public $vendor;

}