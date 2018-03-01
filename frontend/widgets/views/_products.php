<?php
/**
 * Product[] $products
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
use sointula\shop\frontend\widgets\ProductPrices;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?php if (!empty($products)): ?>
    <div class="products">
        <?php foreach ($products as $product): ?>

            <?php $url = Url::toRoute(['/shop/product/show', 'id' => $product->id]); ?>
            <?php $alt = (!empty($product->image->translation)) ? $product->image->translation->alt : ''; ?>
            <?php $imageUrl = $product->image ? $product->image->getThumb() : ''; ?>

            <div class="product">
                <div>
                    <header>
                        <a href="<?= $url; ?>" style="background-image: url(<?= $imageUrl; ?>);">
                            <?= $alt; ?>
                        </a>

                        <?php if (!empty($product->translation->title)): ?>
                            <div class="title-mask">
                                <a href="<?= $url; ?>">
                                </a>
                            </div>
                            <p class="h3">
                                <a href="<?= $url; ?>">
                                    <?= Html::encode($product->translation->title); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </header>

                    <!--PRICES-->
                    <?php $form = ActiveForm::begin([
                        'action' => ['/shop/cart/add'],
                        'options' => [
                            'class' => 'order-form'
                        ]]);
                    ?>
                    <?= ProductPrices::widget([
                        'product' => $product,
                        'form' => $form,
                        'defaultCombination' => $product->defaultCombination,
                        'notAvailableText' => \Yii::t('shop', 'Not available')
                    ]) ?>
                    <?php $form->end() ?>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
<?php endif; ?>
