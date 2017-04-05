<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $combination ProductCombination
 * @var $combinationAttributes ProductCombinationAttribute[]
 * @var $combinationAttributeForm CombinationAttributeForm
 * @var $languageId integer
 * @var $image_form CombinationImageForm
 * @var $combinationImages ProductCombinationImage
 * @var $product Product
 */

use xalberteinsteinx\shop\backend\assets\EditCombinationAsset;
use xalberteinsteinx\shop\backend\components\form\{
    CombinationAttributeForm, CombinationImageForm
};
use xalberteinsteinx\shop\common\entities\{
    Product, Combination, CombinationAttribute, CombinationImage, PriceDiscountType, ShopAttribute
};
use bl\imagable\helpers\FileHelper;
use yii\helpers\{
    ArrayHelper, Html, Url
};
use yii\widgets\ActiveForm;

EditCombinationAsset::register($this);

$this->title = \Yii::t('shop', 'Edit combination');
$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    [
        'label' => Yii::t('shop', 'Products'),
        'url' => ['/shop/product'],
        'itemprop' => 'url'
    ],
    [
        'label' => $combination->product->translation->title,
        'url' => Url::to(['add-combination', 'productId' => $combination->product_id, 'languageId' => $languageId]),
        'itemprop' => 'url'
    ],
    $this->title
];
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2><?= $this->title; ?></h2>
    </div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'action' => [
                'edit-combination',
                'combinationId' => $combination->id,
                'languageId' => $languageId
            ],
            'method' => 'post',
            'options' => [
                'class' => '',
                'data-pjax' => true
            ]
        ]) ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($combination, 'sku'); ?>
            </div>
        </div>
        <div class="row">

            <div class="col-md-4">
                <?= $form->field($combination, 'price')->textInput(['type' => 'number', 'step' => '0.01']); ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($combination, 'discount_type_id')
                    ->dropDownList(
                        ['' => '--none--'] +
                        ArrayHelper::map(PriceDiscountType::find()->asArray()->all(), 'id', 'title')
                    );
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($combination, 'discount')->textInput(['type' => 'number', 'step' => '0.01']); ?>
            </div>
        </div>
        <?= $form->field($combination, 'default')->checkbox(); ?>

        <!--IMAGES-->
        <?php $image_form->product_image_id = ArrayHelper::map($combinationImages, 'product_image_id', 'product_image_id'); ?>
        <?= $form->field($image_form, 'product_image_id')
            ->checkboxList(ArrayHelper::map($product->images, 'id', function ($model) {
                return
                    Html::img('/images/shop-product/' . FileHelper::getFullName(
                            \Yii::$app->shop_imagable->getSmall('shop-product', $model->file_name)));
            }), ['encode' => false]); ?>
        <p>
            <?= \Yii::t('shop', '*This is the images that you uploaded in the product settings. Select necessary images for this combination.'); ?>
        </p>

        <hr>

        <h2><?= \Yii::t('shop', 'Attributes'); ?></h2>

        <p>
            <?= \Yii::t('shop', '*After you add the attribute you must to save the combination.'); ?>
        </p>
        <table id="attributes-list" class="table table-hover">
            <tr>
                <th><?= \Yii::t('shop', 'Attribute'); ?></th>
                <th><?= \Yii::t('shop', 'Value'); ?></th>
                <th><?= \Yii::t('shop', 'Control'); ?></th>
            </tr>

            <tr>
                <th class="col-md-5">
                    <?= Html::dropDownList('attribute', null, ['attribute' => '--none--'] + ArrayHelper::map(ShopAttribute::find()->all(), 'id', 'translation.title'),
                        ['class' => 'form-control', 'id' => 'productcombinationattribute']); ?>
                </th>
                <th class="col-md-5">
                    <?= Html::dropDownList('value', null, ['' => '--none--'],
                        ['class' => 'form-control', 'id' => 'productcombinationvalue']); ?>
                </th>
                <th class="col-md-2">
                    <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary', 'id' => 'add-attribute-value']) ?>
                </th>
            </tr>

            <tr style="display: none;">
                <td></td>
                <td></td>
                <td>
                    <?= Html::button('',
                        [
                            'class' => 'glyphicon glyphicon-remove text-danger btn btn-default btn-sm remove-attribute',
                        ]
                    ) ?>
                </td>
            </tr>
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

        <!--EXISTS COMBINATION ATTRIBUTES-->
        <table class="table table-hover">
            <?php foreach ($combinationAttributes as $existAttribute) : ?>
                <tr>
                    <td class="col-md-5">
                        <?= $existAttribute->productAttribute->translation->title; ?>
                    </td>
                    <td class="col-md-5">
                        <?= $existAttribute->productAttributeValue->translation->value; ?>
                    </td>
                    <td class="col-md-2">
                        <?= Html::a('',
                            Url::to(['remove-combination-attribute', 'combinationAttributeId' => $existAttribute->id]),
                            ['class' => 'glyphicon glyphicon-remove text-danger btn btn-default btn-sm remove-attribute']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'btn btn-primary']) ?>
        <?= Html::a(
            \Yii::t('shop', 'Close'),
            Url::to(['add-combination', 'productId' => $product->id, 'languageId' => $languageId]),
            ['class' => 'btn btn-danger']
        ); ?>
        <?php $form->end() ?>
    </div>
</div>