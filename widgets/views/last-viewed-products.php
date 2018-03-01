<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $products \sointula\shop\common\entities\Product
 */
use sointula\shop\frontend\components\forms\CartForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>

<?php if (!empty($products)) : ?>
    <div class="col-md-12 recommended-products">

        <!--WIDGET TITLE-->
        <p class="recommended-products-title">
            <?= Yii::t('shop', 'Last viewed products') ?>
        </p>

        <!--PRODUCTS-->
        <?php foreach ($products as $product) : ?>
            <div class="text-center product col-md-3">

                <!--Product image-->
                <div class="img">
                    <a href="<?= Url::to(['/shop/product/show', 'id' => $product->product->id]) ?>">
                        <?php if (!empty($product->product->image)) : ?>
                            <?= Html::img($product->product->image->small) ?>
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
                        <a href="<?= Url::to(['/shop/product/show', 'id' => $product->product->id]) ?>">
                            <?= !empty($product->product->translation->title) ? $product->product->translation->title : ''; ?>
                        </a>
                    </p>

                    <div class="price-and-count">
                        <!--Price-->
                        <div class="price col-md-6">
                            <?php if (!empty($product->product->prices)) : ?>
                                <?= $form->field($cart, 'priceId', ['options' => ['class' => '']])
                                    ->dropDownList(ArrayHelper::map($product->product->discountPrices, 'id',
                                        function ($model) {
                                            $priceItem = $model->translation->title . ' - ' . \Yii::$app->formatter->asCurrency($model->salePrice);
                                            return $priceItem;
                                        }))->label(\Yii::t('shop', 'Price')); ?>
                            <?php else : ?>
                                <p class="label-price"><?= Yii::t('shop', 'Price'); ?></p>
                                <p class="standart-price">
                                    <?= \Yii::$app->formatter->asCurrency($product->product->discountPrice); ?>
                                </p>
                            <?php endif; ?>
                            <?= $form->field($cart, 'productId')->hiddenInput(['value' => $product->product->id])->label(false); ?>
                        </div>

                        <!--Count-->
                        <div class="count col-md-6">
                            <?= $form->field($cart, 'count', ['options' => ['class' => '']])->
                            textInput(['type' => 'number', 'min' => 1, 'value' => 1])->label(\Yii::t('shop', 'Count'));
                            ?>
                        </div>
                    </div>

                    <!--Button-->
                    <div class="buy">
                        <?= Html::submitButton(
                            Html::tag('span', '', ['class' => 'fa fa-shopping-cart']),
                            ['class' => 'btn btn-tight btn-primary']); ?>
                    </div>
                </div>
                <?php $form::end(); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>