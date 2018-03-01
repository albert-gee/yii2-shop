<?php
namespace sointula\shop\frontend;
use Yii;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'sointula\shop\frontend\controllers';

    /**
     * Enables attribute combinations in product card
     * @var bool
     */
    public $enableCombinations = false;

    /**
     * @var string|array
     * Partner manager e-mail, on which partner request will be sent.
     */
    public $partnerManagerEmail;

    /**
     * @var string
     * From this e-mail partner request will be sent to Partner manager and partner.
     */
    public $senderEmail;

    /**
     * @var array
     * If log is enabled, viewed products will save for users.
     */
    public $log = [
        'enabled' => true,
        'maxProducts' => 'all' // Max number of viewed products by one user.
    ];

    /**
     * @var bool
     * It enables the conversion of prices by the currency
     */
    public $enableCurrencyConversion = false;

    /**
     * If true category products will be shown with child categories products.
     * @var bool
     */
    public $showChildCategoriesProducts = false;

    /**
     * Enables rounding for prices
     * @var bool
     */
    public $enablePriceRounding = true;


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
                'basePath'       => '@vendor/sointula/yii2-shop/frontend/messages',
        ];
    }
}
