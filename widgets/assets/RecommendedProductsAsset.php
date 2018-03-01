<?php
namespace sointula\shop\widgets\assets;

use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class RecommendedProductsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sointula/yii2-shop/widgets/assets/src';

    public $css = [
        'css/recommended-products.css'
    ];
}
