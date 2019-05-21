<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $recommendedProducts \albertgeeca\shop\common\entities\Product
 */
use albertgeeca\shop\frontend\components\forms\CartForm;
use albertgeeca\shop\frontend\widgets\ProductPrices;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>

<?php if (!empty($recommendedProducts)) : ?>
    <div class="col-md-12 recommended-products product">

        <!--WIDGET TITLE-->
        <p class="recommended-products-title">
            <?= Yii::t('shop', 'Recommended products') ?>
        </p>

        <!--PRODUCTS-->
        <?php foreach ($recommendedProducts as $recommendedProduct) : ?>
            <div class="text-center product col-md-3">

                <!--Product image-->
                <div class="img">
                    <a href="<?= Url::to(['/shop/product/show', 'id' => $recommendedProduct->id]) ?>">
                        <?php if (!empty($recommendedProduct->image)) : ?>
                            <?= Html::img($recommendedProduct->image->small) ?>
                        <?php endif; ?>
                    </a>
                </div>

                <!--Content block-->
                <?php $form = ActiveForm::begin([
                    'method' => 'post',
                    'action' => ['/shop/cart/add'],
                    'options' => [
                        '_fields' => [
                            'class' => 'col-md-4'
                        ]
                    ]
                ]);
                $cart = new CartForm();
                ?>
                <div class="product-content">
                    <!--Product title-->
                    <p class="product-title">
                        <a href="<?= Url::to(['/shop/product/show', 'id' => $recommendedProduct->id]) ?>">
                            <?= !empty($recommendedProduct->translation->title) ? $recommendedProduct->translation->title : ''; ?>
                        </a>
                    </p>

                    <!--Button-->
                    <div class="buy">
                        <a href="<?= Url::to(['/shop/product/show', 'id' => $recommendedProduct->id]); ?>" class="btn btn-tight btn-primary">
                            <?= Html::tag('span', '', ['class' => 'fa fa-shopping-cart']); ?>
                        </a>
                    </div>
                </div>
                <?php $form::end(); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>