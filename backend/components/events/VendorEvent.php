<?php
namespace sointula\shop\backend\components\events;

use sointula\shop\common\entities\ProductCountry;
use sointula\shop\common\entities\Vendor;
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