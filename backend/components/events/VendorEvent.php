<?php
namespace xalberteinsteinx\shop\backend\components\events;

use xalberteinsteinx\shop\common\entities\ProductCountry;
use xalberteinsteinx\shop\common\entities\Vendor;
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