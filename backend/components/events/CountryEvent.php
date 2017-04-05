<?php
namespace xalberteinsteinx\shop\backend\components\events;

use xalberteinsteinx\shop\common\entities\ProductCountry;
use yii\base\Event;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CountryEvent extends Event
{

    /**
     * @var ProductCountry
     */
    public $country;

}