<?php
/**
 * @var integer $languageId
 * @var \albertgeeca\shop\common\entities\Product $product
 * @var \albertgeeca\shop\common\entities\Combination $combination
 * @var \albertgeeca\shop\common\entities\CombinationTranslation $combinationTranslation
 * @var \albertgeeca\shop\backend\components\form\CombinationImageForm $image_form
 * @var \albertgeeca\shop\common\entities\Price[] $prices
 * @var \albertgeeca\shop\backend\components\form\CombinationAttributeForm $combinationAttributeForm
 * @var \albertgeeca\shop\common\entities\ProductImage[] $productImages
 */

use rmrevin\yii\fontawesome\FA;
use albertgeeca\shop\common\components\user\models\UserGroup;
use albertgeeca\shop\common\entities\PriceDiscountType;
use albertgeeca\shop\common\entities\ProductAvailability;
use albertgeeca\shop\common\entities\ShopAttribute;
use bl\imagable\helpers\FileHelper;
use marqu3s\summernote\Summernote;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

?>
<?php $form = ActiveForm::begin([
    'action' => [
        'add-combination',
        'productId' => $product->id,
        'languageId' => $languageId
    ],
    'method' => 'post',
    'options' => [
        'class' => '',
        'data-pjax' => true
    ]
]); ?>
<div class="modal-body">


    <div class="row">

        <div class="col-md-12">
            <!--SKU-->
            <?= $form->field($combination, 'sku'); ?>
            <!--DESCRIPTION-->
            <?= $form->field($combinationTranslation, 'description', [
                'inputOptions' => [
                    'class' => 'form-control'
                ]
            ])->widget(Summernote::className())->label(\Yii::t('shop', 'Short description'));
            ?>

            <!--IMAGES-->
            <?= $form->field($image_form, 'product_image_id')
                ->checkboxList(ArrayHelper::map($productImages, 'id', function ($model) {
                    return
                        Html::img('/images/shop-product/' . FileHelper::getFullName(
                                \Yii::$app->shop_imagable->getSmall('shop-product', $model->file_name)));
                }), [
                    'encode' => false,
                    'item' => function ($index, $label, $name, $checked, $value) {
                        return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                        Html::checkbox($name, $checked, ['value' => $value]) . $label . '</label>';
                    }]);
            ?>

            <!--AVAILABILITY-->
            <?= $form->field($combination, 'availability', [
                'inputOptions' => [
                    'class' => 'form-control'
                ]
            ])->dropDownList(
                ArrayHelper::map(ProductAvailability::find()->all(), 'id', 'translation.title')
            ); ?>

            <!--NUMBER-->
            <?= $form->field($combination, 'number', [
                'inputOptions' => [
                    'class' => 'form-control'
                ]
            ])->textInput()
            ?>

            <!--DEFAULT-->
            <?= $form->field($combination, 'default')->checkbox(['class' => '']); ?>

            <!--PRICES-->
            <table id="attributes-list" class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-md-2 text-center"><?= \Yii::t('shop', 'User group'); ?></th>
                    <th class="col-md-3 text-center"><?= \Yii::t('shop', 'Price'); ?></th>
                    <th class="col-md-2 text-center"><?= \Yii::t('shop', 'Discount type'); ?></th>
                    <th class="col-md-3 text-center"><?= \Yii::t('shop', 'Discount'); ?></th>
                    <th class="col-md-2 text-center"><?= \Yii::t('shop', 'Control'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($prices as $key => $price) : ?>
                    <tr class="prices-inputs">
                        <td class="text-center">
                            <p>
                                <b>
                                    <?= UserGroup::findOne($key)->translation->title; ?>
                                </b>
                            </p>
                        </td>
                        <td>
                            <?= $form->field($price, "[$key]price")->textInput(['type' => 'number', 'step' => '0.01'])->label(false); ?>
                        </td>
                        <td>
                            <?= $form->field($price, "[$key]discount_type_id")
                                ->dropDownList(
                                    ['' => '--none--'] +
                                    ArrayHelper::map(PriceDiscountType::find()->asArray()->all(), 'id', 'title')
                                )->label(false);
                            ?>
                        </td>
                        <td>
                            <?= $form->field($price, "[$key]discount")->textInput(['type' => 'number', 'step' => '0.01'])->label(false); ?>
                        </td>
                        <td>
                            <?= Html::button(
                                Html::tag('span', \Yii::t('shop', 'Clear'), ['class' => '']), [
                                'class' => 'btn btn-warning clear-prices-tr col-md-8 col-md-offset-2'
                            ]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!--ATTRIBUTES-->
            <table id="attributes-list" class="table table-bordered col-md-12">
                <thead>
                <tr>
                    <th><?= \Yii::t('shop', 'Attribute'); ?></th>
                    <th><?= \Yii::t('shop', 'Value'); ?></th>
                    <th><?= \Yii::t('shop', 'Control'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th class="col-md-5">
                        <?= Html::dropDownList('attribute', null, ['attribute' => '--none--'] + ArrayHelper::map(ShopAttribute::find()->all(), 'id', 'translation.title'),
                            ['class' => 'form-control', 'id' => 'productcombinationattribute']); ?>
                    </th>
                    <th class="col-md-5">
                        <?= Html::radioList('value', null, ['' => '--none--'],
                            ['class' => '', 'id' => 'productcombinationvalue']); ?>
                    </th>
                    <th class="col-md-2">
                        <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary', 'id' => 'add-attribute-value']) ?>
                    </th>
                </tr>

                <tr style="display: none;">
                    <td></td>
                    <td></td>
                    <td>
                        <?= Html::button(FA::i(FA::_REMOVE),
                            [
                                'class' => 'btn btn-danger remove-attribute',
                            ]
                        ) ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <div id="attribute-inputs">
                <?= $form->field($combinationAttributeForm, 'attribute_id[]')
                    ->hiddenInput(['class' => 'hidden-attribute'])->label(false);
                ?>
            </div>
            <div id="value-inputs">
                <?= $form->field($combinationAttributeForm, 'attribute_value_id[]')
                    ->hiddenInput(['class' => 'hidden-value'])->label(false);
                ?>
            </div>
            <p>
                *<?= \Yii::t('shop', 'Do not duplicate combinations of attributes, it may cause a malfunction of the script. All combinations must be distinguished from each other.'); ?>
            </p>



        </div>
    </div>
</div>

<div class="modal-footer">
    <?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'btn btn-primary']) ?>
    <button type="button" class="btn btn-default" data-dismiss="modal"><?=\Yii::t('shop', 'Close'); ?></button>
</div>
<?php $form->end() ?>
<?php $this->registerCss('
.texture {width: 50px;}
.color {width: 50px; height: 50px;}
'
); ?>