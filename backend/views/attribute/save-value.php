<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $attributeValueTranslation \xalberteinsteinx\shop\common\entities\ShopAttributeValueTranslation
 * @var $languageId integer
 */
use xalberteinsteinx\shop\common\entities\ShopAttributeType;
use kartik\file\FileInput;
use kartik\widgets\ColorInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?= \xalberteinsteinx\shop\widgets\LanguageSwitcher::widget([
    'selectedLanguage' => \bl\multilang\entities\Language::findOne($attributeValueTranslation->language_id)
]); ?>

<?php $valueForm = ActiveForm::begin([
    'method' => 'post',
    'options' => ['data-pjax' => false, 'enctype' => 'multipart/form-data'],
    'action' => [
        'attribute/save-value',
        'languageId' => $languageId,
        'id' => $attributeValueTranslation->shopAttributeValue->id
    ],
]); ?>

<?php if ($attributeValueTranslation->shopAttributeValue->shopAttribute->type_id == ShopAttributeType::TYPE_COLOR ||
    $attributeValueTranslation->shopAttributeValue->shopAttribute->type_id == ShopAttributeType::TYPE_TEXTURE): ?>

    <?= $valueForm->field($attributeTextureModel, 'title')->textInput([
        'value' => (!empty($attributeValueTranslation->shopAttributeValue->getTranslation($languageId))) ?
            $attributeValueTranslation->shopAttributeValue->getTranslation($languageId)->colorTexture->title : ''
    ]); ?>
<?php endif; ?>

<?php if ($attributeValueTranslation->shopAttributeValue->shopAttribute->type_id == 3) : ?>
    <?= $valueForm->field($attributeTextureModel, 'color')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => \Yii::t('shop', 'Select color'),
            'value' => $attributeValueTranslation->colorTexture->color ?? '#00ff00'],
    ]); ?>

<?php elseif ($attributeValueTranslation->shopAttributeValue->shopAttribute->type_id == 4) : ?>
    <div>
        <?= (!empty($attributeValueTranslation->colorTexture)) ?
            $attributeValueTranslation->colorTexture->attributeTexture :
            $attributeValueTranslation->shopAttributeValue->translation->colorTexture->attributeTexture; ?>
    </div>
    <?= $valueForm->field($attributeTextureModel, 'imageFile')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
    ]); ?>
<?php else : ?>
    <?= $valueForm->field($attributeValueTranslation, 'value')
        ->textInput(['maxlength' => true, 'class' => "form-control"])->label(false) ?>
<?php endif; ?>


    <div class="form-group">
        <?= Html::submitButton($attributeValueTranslation->isNewRecord ?
            Yii::t('shop', 'Add') : Yii::t('shop', 'Update'), ['class' => 'pjax btn btn-primary']) ?>
        <?= Html::a('Cancel', \yii\helpers\Url::to(['save',
            'attrId' => $attributeValueTranslation->shopAttributeValue->shopAttribute->id,
            'languageId' => $attributeValueTranslation->language_id
        ]), ['class' => 'btn btn-danger']); ?>
    </div>

<?php ActiveForm::end(); ?>