<?php
namespace sointula\shop\widgets\assets;

use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class GraphAsset extends AssetBundle
{
    public $sourcePath = '@vendor/albert-sointula/yii2-shop/widgets/assets/src';

    public $css = [
    ];
    public $js = [
        'js/graph.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}