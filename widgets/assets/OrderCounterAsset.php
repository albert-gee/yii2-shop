<?php
namespace sointula\shop\widgets\assets;

use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class OrderCounterAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sointula/yii2-shop/widgets/assets/src';

    public $css = [
        'css/order-counter.css'
    ];
    public $js = [
    ];
    public $depends = [
    ];
}