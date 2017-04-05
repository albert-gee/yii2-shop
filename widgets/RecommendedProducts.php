<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget displays the recommended products
 */

namespace xalberteinsteinx\shop\widgets;

use xalberteinsteinx\shop\common\entities\Product;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class RecommendedProducts extends Widget
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $prevProductsLimit = 2;

    /**
     * @var int
     */
    public $nextProductsLimit = 2;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();

        $products = $this->findRecommendedProducts($this->id);
        if (!empty($products)) {
            return $this->render('recommended-products', [
                'recommendedProducts' => $products
            ]);
        }
        else return false;
    }

    /**
     * @param $id
     * @return array|bool|Product[]
     */
    private function findRecommendedProducts($id) {

        if (!empty($id)) {
            $product = Product::findOne($id);
            $categoryId = $product->category_id;

            $previous = Product::find()->where(['<', 'id', $id])
                ->andWhere(['category_id' => $categoryId])
                ->orderBy(['id' => SORT_DESC])
                ->limit($this->prevProductsLimit)
                ->all();

            $next = Product::find()->where(['>', 'id', $id])
                ->andWhere(['category_id' => $categoryId])
                ->orderBy(['id' => SORT_ASC])
                ->limit($this->nextProductsLimit)
                ->all();

            $products = ArrayHelper::merge($previous, $next);

            return $products;
        }

        return false;
    }
}