<?php
namespace sointula\shop\widgets;

use sointula\shop\common\entities\Product;
use sointula\shop\common\entities\ViewedProduct;
use yii\base\Widget;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget displays the last viewed products by user.
 *
 * Example:
 * <?= LastViewedProducts::widget([
 * 'num' => 4
 * ]); ?>
 */
class LastViewedProducts extends Widget
{

    public $num = 5;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        parent::run();

        $products = ViewedProduct::find()->where(['user_id' => \Yii::$app->user->id])
            ->limit($this->num)->all();

        if (!empty($products)) {
            return $this->render('last-viewed-products',
                [
                    'products' => $products
                ]);
        }
        else return false;
    }
}