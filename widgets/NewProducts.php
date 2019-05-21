<?php
namespace albertgeeca\shop\widgets;

use albertgeeca\shop\common\components\CartComponent;
use albertgeeca\shop\common\entities\Product;
use yii\base\Widget;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget shows last orders.
 *
 * Example:
 * <?= NewProducts::widget([
 * 'num' =>10
 * ]); ?>
 *
 */
class NewProducts extends Widget
{
    /**
     * @var integer
     * Number of orders which will be shown.
     */
    public $num = 10;

    /**
     * @var CartComponent
     */
    private $cart;

    public function run()
    {
        $this->cart = \Yii::$app->cart;

        if (!\Yii::$app->user->can('createProductWithoutModeration')) {
            $products = Product::find()->where(['status' => Product::STATUS_SUCCESS])
                ->andWhere(['owner' => \Yii::$app->user->id])->orderBy(['id' => SORT_DESC])->limit($this->num)->all();
        }
        else {
            $products = Product::find()->where(['status' => Product::STATUS_SUCCESS])->orderBy(['id' => SORT_DESC])->limit($this->num)->all();
        }

        $showOwners = $this->cart->saveToDataBase;

        return $this->render('new-products', [
            'products' => $products,
            'showOwners' => $showOwners
        ]);
    }

}