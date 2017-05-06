<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $combinations \xalberteinsteinx\shop\common\entities\Combination[]
 * @var $combination \xalberteinsteinx\shop\common\entities\Combination
 * @var $combinationAttributeForm xalberteinsteinx\shop\backend\components\form\CombinationAttributeForm
 * @var $product \xalberteinsteinx\shop\common\entities\Product
 * @var $languageId integer
 * @var $image_form \xalberteinsteinx\shop\backend\components\form\CombinationImageForm
 * @var $prices array
 *
 */

use xalberteinsteinx\shop\backend\assets\EditCombinationAsset;
use xalberteinsteinx\shop\common\entities\PriceDiscountType;
use xalberteinsteinx\shop\common\entities\ProductAvailability;
use xalberteinsteinx\shop\common\entities\ShopAttribute;
use bl\imagable\helpers\FileHelper;
use marqu3s\summernote\Summernote;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

EditCombinationAsset::register($this);
$GLOBALS['combinationImages'] = $combinationImagesIds;
?>

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

<h1><?= \Yii::t('shop', 'Change combination'); ?></h1>

<div class="row">

    <div class="col-md-6">
        <!--SKU-->
        <?= $form->field($combination, 'sku'); ?>
        <!--DESCRIPTION-->
        <?= $form->field($combinationTranslation, 'description', [])
            ->widget(Summernote::className())->label(\Yii::t('shop', 'Short description'));
        ?>
    </div>

    <div class="col-md-6">
        <!--IMAGES-->
        <?= $form->field($image_form, 'product_image_id')
            ->checkboxList(ArrayHelper::map($productImages, 'id', function ($model) {
                return
                    Html::img('/images/shop-product/' . FileHelper::getFullName(
                            \Yii::$app->shop_imagable->getSmall('shop-product', $model->file_name)));
            }), [
                'encode' => false,
                'item' => function ($index, $label, $name, $checked, $value) {
                    if (in_array($value, $GLOBALS['combinationImages'])) {
                        $checked = true;
                    }
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
        <?= $form->field($combination, 'default')->checkbox(); ?>
    </div>
</div>

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
                        <?= $price->combinationPrice->userGroup->translation->title; ?>
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
<table id="attributes-list" class="table table-bordered">
    <thead>
    <tr>
        <th class="col-md-5"><?= \Yii::t('shop', 'Attribute'); ?></th>
        <th class="col-md-5"><?= \Yii::t('shop', 'Value'); ?></th>
        <th class="col-md-2"><?= \Yii::t('shop', 'Control'); ?></th>
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
            <?= Html::submitButton(\Yii::t('shop', 'Add'),
                ['class' => 'btn btn-primary col-md-8 col-md-offset-2', 'id' => 'add-attribute-value']) ?>
        </th>
    </tr>

    <tr style="display: none;">
        <td></td>
        <td></td>
        <td>
            <?= Html::button('',
                [
                    'class' => 'glyphicon glyphicon-remove text-default btn btn-danger btn-sm remove-attribute col-md-4 col-md-offset-4',
                ]
            ) ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="table table-bordered">
    <?php foreach ($combinationAttributes as $combinationAttribute): ?>
        <tr>
            <td class="col-md-5"><?= $combinationAttribute->productAttribute->translation->title; ?></td>
            <td class="col-md-5"><?= $combinationAttribute->productAttributeValue->translation->value; ?></td>
            <td class="col-md-2"><?= Html::a('',
                    Url::to(['/shop/combination/remove-combination-attribute', 'id' => $combinationAttribute->id]),
                    ['class' => 'glyphicon glyphicon-remove text-default btn btn-danger btn-sm col-md-4 col-md-offset-4']); ?></td>
        </tr>
    <?php endforeach; ?>
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

<div class="box-content">
    <?= Html::a(
        \Yii::t('shop', 'Cancel'),
        Url::to(['/shop/product/add-combination', 'productId' => $combination->product_id, 'languageId' => $combinationTranslation->language_id]),
        ['class' => 'btn btn-danger pull-right']); ?>

    <?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'btn btn-primary pull-right m-r-xs']) ?>
</div>

<p>
    *<?= \Yii::t('shop', 'Do not duplicate combinations of attributes, it may cause a malfunction of the script. All combinations must be distinguished from each other.'); ?>
</p>
<?php $form->end() ?>


<?php $this->registerCss('
.texture {width: 50px;}
.color {width: 50px; height: 50px;}
'
); ?>
