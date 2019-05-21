<?php
namespace albertgeeca\shop\frontend\assets;
use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class CartAsset extends AssetBundle
{
    public $sourcePath = '@vendor/albert-sointula/yii2-shop/frontend/web';

    public $css = [
        'css/cart.css',
    ];

    public $js = [
    ];

    public $depends = [
    ];
}