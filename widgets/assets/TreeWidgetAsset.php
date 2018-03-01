<?php
namespace sointula\shop\widgets\assets;

use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class TreeWidgetAsset extends AssetBundle
{
    public $sourcePath = '@vendor/albert-sointula/yii2-shop/widgets/assets/src';

    public $css = [
        'css/tree.css'
    ];
    public $js = [
        'js/tree.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
