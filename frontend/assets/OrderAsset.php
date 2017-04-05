<?php

namespace xalberteinsteinx\shop\frontend\assets;

use yii\web\AssetBundle;

/**
 * Asset for OrderController actions
 */
class OrderAsset extends AssetBundle
{
    public $sourcePath = '@vendor/xalberteinsteinx/yii2-shop/frontend/web';

    public $css = [
        'css/order.css',
    ];
    public $js = [
    ];
    public $depends = [
    ];
}
