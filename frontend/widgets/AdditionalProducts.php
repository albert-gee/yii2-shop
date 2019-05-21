<?php
namespace albertgeeca\shop\frontend\widgets;

use albertgeeca\shop\common\entities\ProductAdditionalProduct;
use albertgeeca\shop\frontend\widgets\assets\AdditionalProductsAsset;
use albertgeeca\shop\frontend\widgets\models\AdditionalProductForm;
use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveForm;


/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class AdditionalProducts extends Widget
{

    /**
     * @var integer
     */
    public $productId;

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $attribute;

    public function init()
    {
        AdditionalProductsAsset::register($this->getView());
    }

    public function run()
    {
        parent::run();

        $productAdditionalProducts = ProductAdditionalProduct::find()
            ->joinWith('additionalProduct')
            ->where(['show' => true, 'product_id' => $this->productId])
            ->orderBy('position')
            ->all();

        $form = new AdditionalProductForm();

        return $this->render('additional-products/index',
            [
                'productAdditionalProducts' => $productAdditionalProducts,
                'form' => $this->form,
                'model' => $form,
                'modelAttribute' => $this->attribute
            ]);

    }
}