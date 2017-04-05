<?php
namespace xalberteinsteinx\shop\backend\assets;
use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class EditProductAsset extends AssetBundle
{
    public $sourcePath = '@vendor/xalberteinsteinx/shop/backend/web';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/title-generation.js',
        'js/add-additional.js',
    ];

    public $depends = [
        'xalberteinsteinx\shop\backend\assets\ProductAsset',
        'xalberteinsteinx\shop\backend\assets\InputTreeAsset'
    ];
}