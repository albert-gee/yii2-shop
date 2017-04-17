<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $products \bl\cms\shop\common\entities\Product | \bl\cms\cart\models\OrderProduct
 */
use yii\bootstrap\Html;

?>

<table style="width: 100%; margin: 0 auto;">
    <thead>
    <tr style="background-color: #ececec; font-size: 16px; text-align: center;">
        <th style="margin: 0; padding: 5px; line-height: 30px; height: 30px;"><?= Yii::t('cart', 'Title') ?></th>
        <th style="margin: 0; padding: 5px; line-height: 30px; height: 30px;"><?= Yii::t('cart', 'Price') ?></th>
        <th style="margin: 0; padding: 5px; line-height: 30px; height: 30px;"><?= Yii::t('cart', 'Count') ?></th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($products as $orderProduct): ?>
        <?php $combination = (\Yii::$app->getModule('shop')->enableCombinations && !empty($orderProduct->combination_id)) ?
            $orderProduct->combination : NULL; ?>
        <tr style="padding: 5px 0; background-color: #f7f7f7;">
            <!--TITLE, COMBINATION ATTRIBUTES AND ADDITIONAL PRODUCTS-->
            <td style="padding: 5px 5px 0;">
                <!--PRODUCT TITLE-->
                <?php if (!empty($orderProduct->product->translation)): ?>
                    <?php $url = \Yii::$app->urlManager->createAbsoluteUrl(['/shop/product/show', 'id' => $orderProduct->product->id]);
                    echo Html::a($orderProduct->product->translation->title, $url);
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
                <?php if (!empty($orderProduct->orderProductAdditionalProducts)): ?>
                    <ul>
                        <?php foreach ($orderProduct->orderProductAdditionalProducts as $orderProductAdditionalProduct): ?>
                            <li>
                                <?= $orderProductAdditionalProduct->additionalProduct->translation->title .
                                ', ' . $orderProductAdditionalProduct->number . ' ' . \Yii::t('cart', 'pieces.') .
                                ' - ' . \Yii::$app->formatter->asCurrency($orderProductAdditionalProduct->additionalProduct->discountPrice); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($orderProduct->additionalProduct)): ?>
                    <p>
                        <b><?= \Yii::t('shop', 'Additional products'); ?></b>
                    </p>
                    <ul>
                        <?php foreach ($orderProduct->additionalProduct as $additionalProduct): ?>
                            <li>
                                <?= $additionalProduct->translation->title; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </td>

            <!--PRICE-->
            <td style="text-align: center;"><?= Yii::$app->formatter->asCurrency((!empty($combination)) ?
                    $combination->price->discountPrice :
                    $orderProduct->product->getDiscountPrice()); ?></td>
            <!--NUMBER-->
            <td style="text-align: center"><?= $orderProduct->count; ?></td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>