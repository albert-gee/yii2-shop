<?php
namespace sointula\shop\frontend\widgets\assets;

use yii\web\AssetBundle;


/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductCombinationAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sointula/yii2-shop/frontend/widgets/assets/src';

    public $css = [
        'css/combination.css',
        'css/product-prices-widget.css',
        'css/price-loader.css'
    ];
    public $js = [
        'js/combination.js',
//        'js/product-number.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}