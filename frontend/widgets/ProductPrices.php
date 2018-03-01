<?php
namespace sointula\shop\frontend\widgets;

use sointula\shop\frontend\widgets\assets\ProductCombinationAsset;
use yii\base\Widget;
use yii\widgets\ActiveForm;
use sointula\shop\frontend\components\forms\CartForm;
use sointula\shop\common\entities\{
    Product, Combination
};
use sointula\shop\frontend\widgets\assets\ProductPricesAsset;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductPrices extends Widget
{

    /**
     * @var Product
     */
    public $product;

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var Combination
     */
    public $defaultCombination;

    /**
     * If there is not combination this text will be displayed.
     * @var string
     */
    public $notAvailableText = 'Not available';

    /**
     * Path to widget index view
     * @var string
     */
    public $view;

    /**
     * @var string
     */
    public $renderView;

    /**
     * @var bool
     */
    public $showCounter = true;

    /**
     * Enables cache for combinations
     * @var bool
     */
    public $enableCache = false;

    /**
     * Sets cache duration
     * @var int
     */
    public $cacheDuration = 3600;

    /**
     * @var bool
     */
    public $showAdditionalProducts = true;

    /**
     * If cacheDuration is empty it will be used
     * @var array
     */
    public $dependency = [
        'class' => 'yii\caching\DbDependency',
        'sql' => 'SELECT MAX(update_time) FROM (
                  SELECT update_time FROM shop_combination UNION 
                  SELECT update_time FROM shop_combination_attribute UNION
                  SELECT update_time FROM shop_combination_image UNION
                  SELECT update_time FROM shop_combination_price UNION
                  SELECT update_time FROM shop_combination_translation)temp;'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (\Yii::$app->getModule('shop')->enableCombinations && $this->product->hasCombinations()) {
            $this->renderView = 'combinations';
        } else {
            $this->renderView = 'base-price';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();

        return $this->render($this->view ?? 'product-prices/index',
            [
                'renderView' => $this->renderView,
                'params' => [
                    'product' => $this->product,
                    'form' => $this->form,
                    'cart' => new CartForm(),
                    'defaultCombination' => $this->defaultCombination,
                    'notAvailableText' => $this->notAvailableText,
                    'showCounter' => $this->showCounter,
                    'enableCache' => $this->enableCache,
                    'cacheDuration' => $this->cacheDuration,
                    'dependency' => $this->dependency,
                    'showAdditionalProducts' => $this->showAdditionalProducts
                ]
            ]
        );
    }
}