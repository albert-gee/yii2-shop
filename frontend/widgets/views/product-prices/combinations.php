<?php
/**
 * If Shop module $enableCombinations property is true
 * and if there are product combinations attributes this view will be displayed.
 *
 * @var $product \albertgeeca\shop\common\entities\Product
 * @var $form \yii\widgets\ActiveForm
 * @var $cart \albertgeeca\shop\frontend\components\forms\CartForm
 * @var $defaultCombination \albertgeeca\shop\common\entities\Combination
 * @var $notAvailableText string
 *
 * @var $product->productAttributes ShopAttribute[] Attributes that are present in the combinations of this product
 *
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
use albertgeeca\shop\common\entities\ShopAttributeType;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

global $globalDefaultCombination;
$globalDefaultCombination = $defaultCombination;
$id = 0;
?>

<div class="combinations-values" data-product-id="<?= $product->id; ?>">

    <?php foreach ($product->productAttributes as $productAttribute) : ?>
        <p class="attribute-title">
            <?= $productAttribute->translation->title; ?>
        </p>

        <?php $combinationsIds = ArrayHelper::getColumn($product->combinations, 'id'); ?>
        <?php $combinationsAttributes = $productAttribute->getProductCombinationAttributes($combinationsIds); ?>

        <?php if ($productAttribute->type_id == ShopAttributeType::TYPE_DROP_DOWN_LIST) : ?>

            <?= $form->field($cart, 'attribute_value_id[' . $product->id . ']', [])
                ->dropDownList(ArrayHelper::map($combinationsAttributes,
                    function ($model) {
                        return json_encode(['attributeId' => $model->attribute_id, 'valueId' => $model->productAttributeValue->id]);
                    },
                    function ($model) {
                        return $model->productAttributeValue->translation->value;
                    }),
                    [
                        'name' => 'CartForm[attribute_value_id][' . $product->id . '-' . $productAttribute->id . ']',
                    ]
                )->label(false); ?>

        <?php else : ?>

            <?php $checked = true;
            echo $form->field($cart, 'attribute_value_id[' . $product->id . ']', [])
                ->radioList(ArrayHelper::map($combinationsAttributes,
                    function ($model) {
                        return json_encode(['attributeId' => $model->attribute_id, 'valueId' => $model->productAttributeValue->id]);
                    },
                    function ($model) {
                        if ($model->productAttribute->type->id == ShopAttributeType::TYPE_TEXTURE) {
                            return $model->productAttributeValue->translation->colorTexture->attributeTexture;
                        } else if ($model->productAttribute->type->id == ShopAttributeType::TYPE_COLOR) {
                            return Html::tag('div', '', [
                                'style' => 'background-color: ' . $model->productAttributeValue->translation->colorTexture->color . ';',
                                'class' => 'attribute-color',
                            ]);
                        }
                        return $model->productAttributeValue->translation->value;
                    }),
                    [
                        'name' => 'CartForm[attribute_value_id][' . $product->id . '-' . $productAttribute->id . ']',
                        'encode' => false,
                        'item' => function ($index, $label, $name, $checked, $value) use ($productAttribute, &$id) {

                            if (!empty($GLOBALS['globalDefaultCombination'])) {
                                foreach ($GLOBALS['globalDefaultCombination']->combinationAttributes as $attribute) {
                                    $serialized = json_encode([
                                        'attributeId' => $attribute->attribute_id,
                                        'valueId' => $attribute->attribute_value_id]);
                                    if ($serialized == $value) $checked = true;
                                }
                            }

                            $output = Html::radio($name, $checked, [
                                'value' => $value,
                                'class' => 'radiobutton project-status-btn',
                                'id' => $name . '-' . $id
                            ]);

                            $wrapperClasses = ($checked) ? 'active' : '';
                            $wrapperClasses .=
                                ($productAttribute->type->id == ShopAttributeType::TYPE_COLOR || $productAttribute->type->id == ShopAttributeType::TYPE_COLOR) ?
                                    ' color-texture': '';
                            $labelClasses = 'radiobutton';
                            if($productAttribute->type->id == ShopAttributeType::TYPE_COLOR ||
                                $productAttribute->type->id == ShopAttributeType::TYPE_TEXTURE) {
                                $labelClasses .= ' color';
                                $wrapperClasses .= ' wrapper-inline';
                            }

                            $output .= Html::label(' ' . $label, $name . '-' . $id++, [
                                'class' => $labelClasses
                            ]);

                            return Html::tag('div', $output, ['class' => $wrapperClasses]);
                        },
                    ]
                )->label(false); ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <?= $this->render('sum', [
        'defaultCombination' => $defaultCombination,
    ]); ?>
</div>