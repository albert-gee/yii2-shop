<?php
namespace xalberteinsteinx\shop\backend\components\events;
use yii\base\Event;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PartnersEvents extends Event
{
    public function __construct(array $config = null)
    {
        parent::__construct($config);
    }

    public $partnerUserId;

}