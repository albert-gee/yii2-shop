<?php
namespace sointula\shop\backend\assets;
use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class CategoriesIndexAsset extends AssetBundle
{
    public $sourcePath = '@vendor/sointula/yii2-shop/backend/web';

    public $css = [
    ];

    public $js = [
        'js/categories-index.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}