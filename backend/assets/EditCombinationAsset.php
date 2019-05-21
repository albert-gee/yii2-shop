<?php
namespace albertgeeca\shop\backend\assets;
use yii\web\AssetBundle;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

class EditCombinationAsset extends AssetBundle
{
    public $sourcePath = '@vendor/albert-sointula/yii2-shop/backend/web';

    public $css = [
    ];

    public $js = [
        'js/edit-combination.js',
        'js/add-combination-price.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}