<?php
namespace sointula\shop\backend;
use Yii;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'sointula\shop\backend\controllers';
    public $defaultRoute = 'shop';

    /**
     * Enables attribute combinations in product card
     * @var bool
     */
    public $enableCombinations = false;

    /**
     * @var bool
     * Enables logging in admin panel
     */
    public $enableLog = false;

    /**
     * @var bool
     * It enables the conversion of prices by the currency
     */
    public $enableCurrencyConversion = false;

    /**
     * Enables rounding for prices
     * @var bool
     */
    public $enablePriceRounding = true;

    /**
     * @var string|array
     * Partner manager e-mail, on which information about new partner products will be sent.
     */
    public $partnerManagerEmail;

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['shop'] =
            Yii::$app->i18n->translations['shop'] ??
            [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath'       => '@vendor/sointula/yii2-shop/backend/messages',
        ];
    }
}