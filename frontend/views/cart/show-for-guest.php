<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\shop\common\entities\Product;
use yii\widgets\MaskedInput;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>\\
 *
 * @var yii\web\View $this
 * @var Product[] $products
 */

$this->title = $this->context->staticPage->translation->title ?? Yii::t('cart', 'Cart');
$this->params['breadcrumbs'][] = $this->title;

$defaultImage = Url::to('default.jpg');
bl\cms\cart\frontend\assets\CartAsset::register($this);
?>

<div class="cart">

    <article>
        <h1 class="title">
            <?= $this->title ?>
        </h1>
        <!--DESCRIPTION-->
        <div>
            <?= $this->context->staticPage->translation->text ?? '' ?>
        </div>

<!--ORDER PRODUCTS TABLE-->
        <table class="products-in-cart">
            <thead>
            <tr>
                <th>
                    <?= Yii::t('cart', 'Image') ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Title') ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Price') ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Count') ?>
                </th>
                <th>
                </th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($products as $product): ?>

                <!--COMBINATIONS-->
                <?php $combination = (\Yii::$app->getModule('shop')->enableCombinations && !empty($product->combinationId)) ?
                    $product->getCombination($product->combinationId) : NULL; ?>
                <tr>
                    <!--COMBINATION IMAGE-->
                    <td class="product-image">
                        <?php if (!empty($combination)) {
                            $image = !empty($combination->images) ? $combination->images[0]->productImage->small : '';
                            if (empty($image)) $image = !empty($product->images) ? $product->images[0]->small : $defaultImage;
                        } else {
                            $image = !empty($product->images) ? $product->images[0]->small : $defaultImage;
                        } ?>

                        <?php if (!empty($image)): ?>
                            <?= Html::img($image, [
                                'alt' => !empty($image->translation) ? $image->translation->alt : '',
                                'title' => !empty($image->translation) ? $image->translation->alt : ''
                            ]) ?>

                        <?php endif; ?>
                    </td>
                    <!--TITLE, COMBINATION ATTRIBUTES AND ADDITIONAL PRODUCTS-->
                    <td class="product-title">
                        <!--PRODUCT TITLE-->
                        <?php if (!empty($product->translation)): ?>
                            <?php $url = Url::toRoute(['/shop/product/show', 'id' => $product->id]);
                            echo Html::a($product->translation->title, $url);
                            ?>
                        <?php endif; ?>
                        <!--COMBINATION-->
                        <?php if (!empty($combination)) : ?>
                            <?php foreach ($combination->combinationAttributes as $attribute) : ?>
                                <p>
                                    <?= $attribute->productAttribute->translation->title; ?>:
                                    <?= $attribute->productAttributeValue->translation->value; ?>
                                </p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <!--ADDITIONAL PRODUCTS-->
                        <?php if (!empty($product->additionalProducts)): ?>
                            <p>
                                <b><?= \Yii::t('cart', 'Additional products'); ?></b>
                            </p>
                            <ul>
                                <?php foreach ($product->additionalProducts as $additionalProduct): ?>
                                    <li>
                                        <?= $additionalProduct->translation->title; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </td>

                    <!--PRICE-->
                    <td class="product-price">
                        <?php $price = (!empty($combination)) ? $combination->price->discountPrice : $product->getDiscountPrice(); ?>
                        <?= Yii::$app->formatter->asCurrency($price ?? 0) ?>
                    </td>
                    <!--NUMBER-->
                    <td class="col-md-1">
                        <?php $form = ActiveForm::begin([
                            'action' => ['/shop/cart/change-items-number', 'productId' => $product->id, 'combinationId' => $combination->id],
                            'options' => [
                                'class' => 'order-form'
                            ]
                        ]) ?>
                        <?= Html::input('number', 'count', $product->count, [
                            'min' => 1,
                            'max' => 1000
                        ]) ?>
                        <?= Html::submitButton(\Yii::t('cart', 'Change'), ['class' => 'button']); ?>
                        <?php $form->end(); ?>
                    </td>
                    <!--REMOVE BUTTON-->
                    <td>
                        <?= Html::a(\Yii::t('cart', 'Remove'), Url::to(['/shop/cart/remove',
                            'productId' => $product->id, 'combinationId' => $product['combinationId']]));
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p class="h3 total">
            <b><?= \Yii::t('cart', 'Total'); ?></b>: <?= \Yii::$app->formatter->asCurrency(\Yii::$app->cart->getTotalCost()); ?>
        </p>

        <!--ORDER FORM-->
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'action' => ['/shop/cart/make-order']
        ]); ?>

        <!-- Personal data -->
        <h2><?= Yii::t('cart', 'Personal data'); ?></h2>
        <div class="personal-data">

            <!--Name-->
            <?= $form->field($profile, 'name')->textInput()->label(Yii::t('cart', 'Имя')); ?>
            <!--Surname-->
            <?= $form->field($profile, 'surname')->textInput()->label(Yii::t('cart', 'Фамилия')); ?>

            <!--Email-->
            <?= $form->field($user, 'email')->textInput()->label(Yii::t('cart', 'E-mail')); ?>
            <!--Phone-->
            <?= $form->field($profile, 'phone')->widget(MaskedInput::className(), ['mask' => '+38(999)999-9999'])
                ->label(Yii::t('cart', 'Телефон')); ?>

        </div>

        <!--DELIVERY METHOD-->
        <?= bl\cms\cart\widgets\Delivery::widget([
            'form' => $form,
            'model' => $order,
            'address' => $address
        ]); ?>

        <!--PAYMENT METHOD-->
        <?php if (Yii::$app->cart->enablePayment) : ?>
            <h2><?=Yii::t('cart', 'Payment method');?></h2>
            <?= \bl\cms\payment\widgets\PaymentSelector::widget([
                'form' => $form,
                'order' => $order,
            ]); ?>
        <?php endif; ?>

        <div class="controls">

            <?= Html::a(
                Yii::t('cart', 'Clear cart'),
                Url::toRoute('/shop/cart/clear'),
                ['class' => 'button danger']
            ); ?>

            <!--SUBMIT BUTTON-->
            <?= Html::submitButton(Yii::t('cart', 'Оформить заказ'), [
                'class' => 'button'
            ]); ?>
        </div>

        <?php $form::end(); ?>
    </article>
</div>
