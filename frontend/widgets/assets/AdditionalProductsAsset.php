<?php
namespace xalberteinsteinx\shop\frontend\widgets\assets;

use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class AdditionalProductsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/xalberteinsteinx/yii2-shop/frontend/widgets/assets/src';

    public $css = [
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}