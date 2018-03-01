<?php
namespace sointula\shop\backend\assets;
use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class AjaxLoaderAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sointula/yii2-shop/backend/web';

    public $css = [
        'css/ajax-loader.css'
    ];
    public $js = [
        'js/ajax-loader.js'
    ];

    public $depends = [
        'yii\widgets\PjaxAsset'
    ];
}