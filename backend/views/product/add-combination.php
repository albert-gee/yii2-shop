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
 *
 */

use xalberteinsteinx\shop\backend\assets\EditCombinationAsset;
use xalberteinsteinx\shop\common\entities\PriceDiscountType;
use xalberteinsteinx\shop\common\entities\ShopAttribute;
use bl\imagable\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

EditCombinationAsset::register($this);

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
]) ?>

    <h1><?= \Yii::t('shop', 'Combinations'); ?></h1>
    <h2><?= \Yii::t('shop', 'Add new combination'); ?></h2>

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
            <?= $form->field($combination, 'sale_type_id')
                ->dropDownList(
                    ['' => '--none--'] +
                    ArrayHelper::map(PriceDiscountType::find()->asArray()->all(), 'id', 'title')
                );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($combination, 'sale')->textInput(['type' => 'number', 'step' => '0.01']); ?>
        </div>
    </div>
<?= $form->field($combination, 'default')->checkbox(); ?>


<?= $form->field($image_form, 'product_image_id')
    ->checkboxList(ArrayHelper::map($productImages, 'id', function ($model) {
        return
            Html::img('/images/shop-product/' . FileHelper::getFullName(
                    \Yii::$app->shop_imagable->getSmall('shop-product', $model->file_name)));
    }), ['encode' => false]); ?>

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

<?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php $form->end() ?>

    <hr>

<?php if (!empty($combinations)): ?>
    <h1><?= Yii::t('shop', 'Combinations list'); ?></h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-md-4 text-center"><?= \Yii::t('shop', 'Attributes'); ?></th>
            <th class="col-md-1 text-center"><?= \Yii::t('shop', 'Price'); ?></th>
            <th class="col-md-1 text-center"><?= \Yii::t('shop', 'Sale type'); ?></th>
            <th class="col-md-1 text-center"><?= \Yii::t('shop', 'Discount'); ?></th>
            <th class="col-md-1 text-center"><?= \Yii::t('shop', 'Default'); ?></th>
            <th class="col-md-3 text-center"><?= \Yii::t('shop', 'Images'); ?></th>
            <th class="col-md-1 text-center"><?= \Yii::t('shop', 'Control'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($combinations as $combination): ?>
            <tr class="text-center">
                <td>
                    <?php foreach ($combination->combinationAttributes as $combinationAttribute) : ?>
                        <p>
                            <?= $combinationAttribute->productAttribute->translation->title; ?>:
                            <?= $combinationAttribute->productAttributeValue->translation->value; ?>
                        </p>
                    <?php endforeach; ?>
                </td>
                <td><?= $combination->price ?? ''; ?></td>
                <td><?= $combination->saleType->title ?? ''; ?></td>
                <td><?= $combination->sale ?? ''; ?></td>
                <td>
                    <?php if ($combination->default) : ?>
                        <i class="fa fa-plus"></i>
                    <?php else : ?>
                        <a href="<?= Url::to(['change-default-combination', 'combinationId' => $combination->id]); ?>">
                            <i class="fa fa-minus"></i>
                        </a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php foreach ($combination->images as $image): ?>
                        <?= Html::img('/images/shop-product/' . FileHelper::getFullName(
                                \Yii::$app->shop_imagable->getSmall('shop-product', $image->productImage->file_name))); ?>
                    <?php endforeach; ?>
                </td>
                <td class="text-center">
                    <?= Html::a('', [
                        'edit-combination',
                        'combinationId' => $combination->id,
                        'languageId' => $languageId
                    ],
                        [
                            'class' => 'glyphicon glyphicon-edit text-warnong btn btn-default btn-sm'
                        ]
                    ) ?>
                    <?= Html::a('', [
                        'remove-combination',
                        'combinationId' => $combination->id,
                    ],
                        [
                            'class' => 'glyphicon glyphicon-remove text-danger btn btn-default btn-sm'
                        ]
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>