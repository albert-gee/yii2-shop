<?php
namespace albertgeeca\shop\backend\assets;
use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class ProductAsset extends AssetBundle
{
    public $sourcePath = '@vendor/albert-sointula/yii2-shop/backend/web';

    public $css = [
        'css/style.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}