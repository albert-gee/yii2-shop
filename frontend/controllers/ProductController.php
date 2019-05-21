<?php
namespace albertgeeca\shop\frontend\controllers;

use Yii;
use albertgeeca\shop\frontend\components\forms\CartForm;
use yii\web\NotFoundHttpException;
use yii\web\{
    Response, Controller
};
use albertgeeca\shop\frontend\traits\EventTrait;
use albertgeeca\shop\common\entities\Combination;
use albertgeeca\shop\frontend\widgets\traits\ProductPricesTrait;
use albertgeeca\shop\common\entities\{
    Category, Product, ProductTranslation
};

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class ProductController extends Controller
{

    use EventTrait;
    use ProductPricesTrait;

    /**
     * Event is triggered before.
     * Triggered with \albertgeeca\shop\frontend\traits\EventTrait.
     */
    const EVENT_BEFORE_SHOW = 'beforeShow';

    /**
     * Event is triggered after creating RegistrationForm class.
     * Triggered with \albertgeeca\shop\frontend\traits\EventTrait.
     */
    const EVENT_AFTER_SHOW = 'afterShow';

    /**
     * Shows Product model
     *
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionShow(int $id)
    {

        $this->trigger(self::EVENT_BEFORE_SHOW, $this->getViewedProductEvent($id));

        $product = Product::find()
            ->where(['show' => true, 'id' => $id, 'status' => Product::STATUS_SUCCESS])
            ->one();

        if (!empty($product)) {

            $this->setSeoData($product);

            $product->updateCounters(['shows' => 1]);

            $this->trigger(self::EVENT_AFTER_SHOW);

            return $this->render('show', [
                'product' => $product,
                'cart' => new CartForm(),
                'defaultCombination' => Combination::find()->where([
                    'product_id' => $id,
                    'default' => true
                ])->one()
            ]);
        } else throw new NotFoundHttpException();
    }

    /**
     * @return mixed
     */
    public function actionXml()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=UTF-8');

        return $this->renderPartial('xml', [
            'categories' => Category::find()->all(),
            'products' => Product::findAll(['export' => true, 'show' => true]),
            'date' => ProductTranslation::find()->orderBy(['update_time' => SORT_DESC])->one()->update_time
        ]);
    }

    /**
     * @return mixed
     */
    public function actionHlxml()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml; charset=UTF-8');

        return $this->renderPartial('hlxml', [
            'categories' => Category::find()->all(),
            'products' => Product::findAll(['show' => true, 'export' => true]),
            'date' => ProductTranslation::find()->orderBy(['update_time' => SORT_DESC])->one()->update_time
        ]);
    }

    /**
     * Sets page title, meta-description and meta-keywords.
     * @param $model
     */
    private function setSeoData($model)
    {
        $this->view->title = !empty(($model->translation->seoTitle)) ?
            strip_tags($model->translation->seoTitle) : strip_tags($model->translation->title);
        $this->view->registerMetaTag([
            'name' => 'description',
            'content' => strip_tags($model->translation->seoDescription) ?? ''
        ]);
        $this->view->registerMetaTag([
            'name' => 'keywords',
            'content' => strip_tags($model->translation->seoKeywords) ?? ''
        ]);
    }
}