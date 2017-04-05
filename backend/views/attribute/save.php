<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $attribute xalberteinsteinx\shop\common\entities\ShopAttribute
 * @var $attributeTranslation xalberteinsteinx\shop\common\entities\ShopAttributeTranslation
 * @var $attributeType [] xalberteinsteinx\shop\common\entities\ShopAttributeType
 * @var $form yii\widgets\ActiveForm
 * @var $languages Language
 * @var $selectedLanguage Language
 * @var $valueModel ShopAttributeValue
 * @var $valueModelTranslation ShopAttributeValueTranslation
 */

use xalberteinsteinx\shop\common\entities\ShopAttribute;
use xalberteinsteinx\shop\common\entities\ShopAttributeType;
use xalberteinsteinx\shop\common\entities\ShopAttributeValueColorTexture;
use bl\multilang\entities\Language;
use kartik\widgets\ColorInput;
use kartik\widgets\FileInput;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('shop', 'Create Shop Attribute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Shop Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (count($languages) > 1): ?>
    <div class="dropdown pull-right">
        <button class="btn btn-warning btn-xs dropdown-toggle" type="button"
                id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="true">
            <?= $selectedLanguage->name ?>
            <span class="caret"></span>
        </button>
        <?php if (count($languages) > 1): ?>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php foreach ($languages as $language): ?>
                    <li>
                        <a href="<?= Url::to([
                            'save',
                            'attrId' => $attribute->id,
                            'languageId' => $language->id]) ?>
                                                ">
                            <?= $language->name ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>

        <div class="panel-body">

            <div class="col-md-6">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($attributeTranslation, 'title')->textInput() ?>

                <!--ATTRIBUTE TYPE-->
                <?php $options = []; ?>
                <?php if(!$attribute->isNewRecord) {
                    if ($attribute->type_id == ShopAttributeType::TYPE_DROP_DOWN_LIST ||
                        $attribute->type_id == ShopAttributeType::TYPE_RADIO_BUTTON) {
                        $options = ['options' => [
                            ShopAttributeType::TYPE_COLOR => ['disabled' => true],
                            ShopAttributeType::TYPE_TEXTURE => ['disabled' => true],
                        ]];
                    }
                    else {
                        $options = ["disabled" => "disabled"];
                    }
                }
                ?>

                <?= $form->field($attribute, 'type_id')
                    ->dropDownList(ArrayHelper::map($attributeType, 'id', 'title'), $options); ?>

                <div class="form-group">
                    <?= Html::submitButton($attribute->isNewRecord ? Yii::t('shop', 'Create') : Yii::t('shop', 'Update'), ['class' => $attribute->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

            <?php if (!empty($dataProvider)) : ?>
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

                                        $colorModel = ShopAttributeValueColorTexture::findOne($model->translation->value);
                                        $color = $colorModel->color;
                                        $title = $colorModel->title;
                                        return Html::tag('div', '', array('style' => 'width: 50px; height: 50px; background-color:' . $color)) .
                                        "<p><i>$title</i></p>";
                                    }
                                    if (ShopAttribute::findOne($model->attribute_id)->type_id == ShopAttribute::TYPE_TEXTURE) {
                                        $textureModel = ShopAttributeValueColorTexture::findOne($model->translation->value);
                                        $texture = $textureModel->getAttributeTexture();
                                        $title = $textureModel->title;
                                        return $texture . "<p><i>$title</i></p>";
                                    }
                                    return $model->translation->value ?? '';
                                },
                                'format' => 'raw'
                            ],
                            /*ACTIONS*/
                            [
                                'headerOptions' => ['class' => 'text-center col-md-4'],
                                'attribute' => \Yii::t('shop', 'Control'),

                                'value' => function ($model) {

                                    return \xalberteinsteinx\shop\widgets\ManageButtons::widget([
                                        'model' => $model,
                                        'action' => 'save-value',
                                        'deleteUrl' => Url::to(['remove-attribute-value', 'attributeValueId' => $model->id])
                                    ]);
                                },
                                'format' => 'raw',
                                'contentOptions' => ['class' => 'col-md-2 text-center'],
                            ],
                        ]
                    ]);
                    ?>

                    <div class="col-md-12">
                        <?php $valueForm = ActiveForm::begin([
                            'method' => 'post',
                            'options' => ['data-pjax' => false, 'enctype' => 'multipart/form-data'],
                            'action' => [
                                'attribute/add-value',
                                'attrId' => $attribute->id,
                                'languageId' => $selectedLanguage->id
                            ],
                        ]); ?>

                        <?php if ($attribute->type_id == ShopAttributeType::TYPE_COLOR ||
                            $attribute->type_id == ShopAttributeType::TYPE_TEXTURE): ?>
                            <?= $valueForm->field($attributeTextureModel, 'title')->textInput(); ?>
                        <?php endif; ?>

                        <?php if ($attribute->type_id == ShopAttributeType::TYPE_COLOR) : ?>
                            <?= $valueForm->field($attributeTextureModel, 'color')->widget(ColorInput::classname(), [
                                'options' => ['placeholder' => \Yii::t('shop', 'Select color'), 'value' => '#00ff00'],
                            ]); ?>

                        <?php elseif ($attribute->type_id == ShopAttributeType::TYPE_TEXTURE) : ?>
                            <?= $valueForm->field($attributeTextureModel, 'imageFile')->widget(FileInput::classname(), [
                                'options' => ['accept' => 'image/*'],
                            ]); ?>
                        <?php else : ?>
                            <?= $valueForm->field($valueModelTranslation, 'value')
                                ->textInput(['maxlength' => true, 'class' => "form-control"])->label(false) ?>
                        <?php endif; ?>

                        <div class="form-group">
                            <?= Html::submitButton($valueModel->isNewRecord ?
                                Yii::t('shop', 'Add') : Yii::t('shop', 'Update'), ['class' => 'col-md-12 pjax btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?= $this->registerCss('
img.texture {
width: 50px;
}
'); ?>