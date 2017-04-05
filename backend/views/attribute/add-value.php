<?php
use xalberteinsteinx\shop\common\entities\ShopAttribute;
use xalberteinsteinx\shop\common\entities\ShopAttributeValueColorTexture;
use kartik\widgets\ColorInput;
use kartik\widgets\FileInput;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="col-md-6">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterRowOptions' => ['class' => 'm-b-sm m-t-sm'],
        'options' => [
            'class' => 'table table-hover table-striped table-bordered'
        ],
        'tableOptions' => [
            'id' => 'my-grid',
            'class' => 'table table-hover'
        ],
        'summary' => "",
        'columns' => [
            'id',
            [
                'attribute' => 'value',
                'value' => function ($model) {

                    $attribute = ShopAttribute::findOne($model->attribute_id);
                    if ($attribute->type_id == ShopAttribute::TYPE_COLOR) {

                        $color = ShopAttributeValueColorTexture::findOne($model->translation->value)->color;
                        return Html::tag('div', '', ['style' => 'width: 50px; height: 50px; background-color:' . $color]);
                    }
                    if (ShopAttribute::findOne($model->attribute_id)->type_id == ShopAttribute::TYPE_TEXTURE) {
                        return ShopAttributeValueColorTexture::findOne($model->translation->value)->getAttributeTexture();
                    }
                    return $model->translation->value;
                },
                'format' => 'raw'
            ],
            /*ACTIONS*/
            [
                'headerOptions' => ['class' => 'text-center col-md-2'],
                'attribute' => \Yii::t('shop', 'Control'),

                'value' => function ($model) {
                    return Html::a('<span class="glyphicon glyphicon-remove"></span>',
                        Url::toRoute(['remove-attribute-value', 'attributeValueId' => $model->id]),
                        ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger pull-right btn-xs pjax']);
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'col-md-2 text-center'],
            ],
        ]
    ]);
    ?>
</div>

<div class="shop-attribute-value-form col-md-6">
    <?php $valueForm = ActiveForm::begin([
        'method' => 'post',
        'options' => ['data-pjax' => false, 'enctype' => 'multipart/form-data'],
        'action' => [
            'attribute/add-value',
            'attrId' => $attribute->id,
            'languageId' => $selectedLanguage->id
        ],
    ]); ?>

    <?php if ($attribute->type_id == 3) : ?>
        <?= $valueForm->field($attributeTextureModel, 'color')->widget(ColorInput::classname(), [
            'options' => ['placeholder' => \Yii::t('shop', 'Select color'), 'value' => '#00ff00'],
        ]); ?>

    <?php elseif ($attribute->type_id == 4) : ?>
        <?= $valueForm->field($attributeTextureModel, 'imageFile')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
        ]); ?>
    <?php else : ?>
        <?= $valueForm->field($valueModelTranslation, 'value')
            ->textInput(['maxlength' => true, 'class' => "form-control"])->label(false) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($valueModel->isNewRecord ?
            Yii::t('shop', 'Add') : Yii::t('shop', 'Update'), ['class' => 'pjax btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


