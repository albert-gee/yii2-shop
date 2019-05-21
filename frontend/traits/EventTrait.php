<?php
namespace albertgeeca\shop\frontend\traits;

use albertgeeca\shop\common\entities\{Product, ViewedProduct};
use yii\base\Event;
use yii\db\Expression;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
trait EventTrait
{
    /**
     * @param $productId integer
     * @return Event
     */
    protected function getViewedProductEvent($productId)
    {
        if ($this->module->log['enabled']) {
            if (!\Yii::$app->user->isGuest) {

                $viewedProduct = ViewedProduct::find()
                    ->where(['product_id' => $productId, 'user_id' => \Yii::$app->user->id])->one();
                $ViewedProductsCount = ViewedProduct::find()
                    ->where(['user_id' => \Yii::$app->user->id])->count();


                if (empty($viewedProduct)) {
                    if ($this->module->log['maxProducts'] != 'all') {
                        if ($ViewedProductsCount > $this->module->log['maxProducts']) {
                            $oldViewedProduct = ViewedProduct::find()
                                ->where(['user_id' => \Yii::$app->user->id])->orderBy('id ASC')->one();
                            $oldViewedProduct->delete();
                        }
                        $this->recordProductView($productId);
                    }
                    else {
                        $this->recordProductViewByLoggedUser($productId, $viewedProduct);
                    }
                    $viewedProduct = new ViewedProduct([
                        'product_id' => $productId,
                        'user_id' => \Yii::$app->user->id
                    ]);

                }
                else $viewedProduct->update_time = new Expression('NOW()');

                $viewedProduct->save();
            }
        }

    }

    /* Number of product views */
    private function recordProductViewByLoggedUser($productId, $viewedProduct = null) {
        if (!empty($productId)) {
            if ($this->module->log['maxProducts'] == 0) {

                if (empty($viewedProduct)) {
                    $this->recordProductView($productId);
                }
            }
        }
    }

    private function recordProductView($productId) {
        $product = Product::findOne($productId);
        $product->views += 1;
        $product->save();
    }

}