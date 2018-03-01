<?php
namespace sointula\shop\frontend\widgets\assets;

use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PaymentAsset extends AssetBundle
{
    public $sourcePath = '@vendor/albert-sointula/yii2-shop/frontend/widgets/assets/src';
    public $css = [
        'css/payment-selector.css',
    ];
    public $js = [
        'js\payment-selector.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}