<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\cart\models\OrderProduct;
use bl\cms\shop\common\entities\Product;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>\\
 *
 * @var yii\web\View $this
 * @var OrderProduct[] $productsFromDB
 * @var Product[] $productsFromSession
 * @var $this ->context->staticPage StaticPage
 */

$this->title = $this->context->staticPage->translation->title ?? Yii::t('cart', 'Cart');
$this->params['breadcrumbs'][] = $this->title;

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
        <div class="controls">
            <?= Html::a(Yii::t('cart', 'Get order'), Url::toRoute('/order'), [
                'class' => 'button text-center'
            ]) ?>
            <?php if (!empty($productsFromDB)) : ?>
                <?= Html::a(
                    Yii::t('cart', 'Clear cart'),
                    Url::toRoute('/shop/cart/clear'),
                    ['class' => 'button danger text-center']
                ); ?>
            <?php endif; ?>
        </div>

        <table class="products-in-cart table table-hover">
            <thead>
            <tr>
                <th class="col-md-2 text-center">
                    <?= Yii::t('cart', 'Photo') ?>
                </th>
                <th class="col-md-3 text-center">
                    <?= Yii::t('cart', 'Title') ?>
                </th>
                <th class="col-md-1 text-center">
                    <?= Yii::t('cart', 'Price') ?>
                </th>
                <th class="col-md-1 text-center">
                    <?= Yii::t('cart', 'Count') ?>
                </th>
                <th class="col-md-1 text-center">
                    <?= \Yii::t('cart', 'Remove'); ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($productsFromDB)): ?>
                <?php foreach ($productsFromDB as $orderProduct): ?>
                    <tr>
                        <td class="col-md-2 text-center">
                            <?php if (!Yii::$app->getModule('shop')->enableCombinations) : ?>
                                <?php if (!empty($orderProduct->combination)): ?>
                                    <?php $src = (!empty($orderProduct->combination->images)) ?
                                        $orderProduct->combination->images[0]->productImage->getSmall() :
                                        $orderProduct->product->image->getSmall() ?? ''; ?>
                                <?php else : ?>
                                    <?php if (!empty($orderProduct->product->image)): ?>
                                        <?php $src = $orderProduct->product->image->getSmall() ?? ''; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?= Html::img($src ?? '', [
                                'alt' => $orderProduct->product->image->translation->alt ?? '',
                                'title' => ''
                            ]) ?>
                        </td>
                        <td class="col-md-3">
                            <?php if (!empty($orderProduct->product->translation->title)): ?>
                                <?php
                                $url = Url::toRoute(['/shop/product/show', 'id' => $orderProduct->product->id]);
                                echo Html::a($orderProduct->product->translation->title, $url);
                                ?>
                                <?php if (!empty($orderProduct->orderProductAdditionalProducts)): ?>
                                    <p>
                                        <b><?= \Yii::t('shop', 'Additional products'); ?>:</b>
                                    </p>
                                    <div class="additional-products">
                                        <ul>
                                            <?php foreach ($orderProduct->orderProductAdditionalProducts as $additionalProduct): ?>
                                                <li>
                                                    <a href="<?= Url::to(['/shop/product', 'id' => $additionalProduct->additionalProduct->id]); ?>">
                                                        <?= $additionalProduct->additionalProduct->translation->title; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="col-md-1">
                            <?php if (\Yii::$app->getModule('shop')->enableCombinations) : ?>
                                <?php if (!empty($orderProduct->combination)) : ?>
                                    <?= Yii::$app->formatter->asCurrency($orderProduct->combination->salePrice) ?>
                                <?php elseif (!empty($orderProduct->price)): ?>
                                    <?= Yii::$app->formatter->asCurrency($orderProduct->price) ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="col-md-1">
                            <?php $form = ActiveForm::begin([
                                'action' => ['/shop/cart/change-items-number', 'productId' => $orderProduct->product->id, 'combinationId' => $orderProduct->combination_id],
                                'options' => [
                                    'class' => 'order-form'
                                ]
                            ]) ?>
                            <?= Html::input('number', 'count', $orderProduct->count, [
                                'min' => 1,
                                'max' => 1000
                            ]) ?>
                            <?php $form->end(); ?>
                        </td>
                        <td class="col-md1-1 text-center">
                            <?php
                            $icon = Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove']);
                            echo Html::a($icon, Url::to(['/shop/cart/remove', 'id' => $orderProduct->product->id]));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </article>
</div>
