<?php
use marqu3s\summernote\Summernote;
use rmrevin\yii\fontawesome\FA;
use sointula\shop\backend\assets\EditProductAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use sointula\shop\common\entities\Vendor;
use sointula\shop\backend\components\form\VendorImage;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var Vendor $vendor
 * @var \sointula\shop\common\entities\VendorTranslation $vendorTranslation
 * @var VendorImage $vendor_image
 */

$this->title = Yii::t('shop', 'Save vendor');

EditProductAsset::register($this);
?>

<div class="box">

    <div class="box-title">
        <h1>
            <?= FA::i(FA::_EDIT) . ' ' . Html::encode($this->title); ?>
        </h1>

        <?= \sointula\shop\widgets\LanguageSwitcher::widget([
            'selectedLanguage' => \bl\multilang\entities\Language::findOne($vendorTranslation->language_id)
        ]); ?>
    </div>

    <div class="box-content">

        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data'],
        ]) ?>
        <!--TITLE-->
        <?= $form->field($vendor, 'title')->textInput(['id' => 'producttranslation-title']) ?>

        <div class="row">

            <div class="col-md-6">
                <h2><?= \Yii::t('shop', 'Basic options'); ?></h2>

                <!--IMAGE-->
                <h4><?= Yii::t('shop', 'Logo'); ?></h4>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($vendor_image, 'imageFile')->fileInput(); ?>
                    </div>
                    <div class="col-md-6">
                        <?php if(!empty($vendor->image_name)): ?>
                            <?= Html::img($vendor_image->getSmall($vendor->image_name), [
                                'class' => 'img-thumbnail thumbnail center-block'
                            ]) ?>
                        <?php else: ?>
                            <?= FA::i(FA::_IMAGE); ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!--DESCRIPTION-->
                <?= $form->field($vendorTranslation, 'description', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->widget(Summernote::className())->label(\Yii::t('shop', 'Description'));
                ?>
            </div>
            <div class="col-md-6">

                <!-- SEO -->
                <h2><?= \Yii::t('shop', 'SEO options'); ?></h2>
                <div class="seo-url">

                    <?= $form->field($vendorTranslation, 'seoUrl', [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'id' => 'producttranslation-seourl'
                        ]
                    ])->label('SEO URL')
                    ?>

                    <?= Html::button(\Yii::t('shop', 'Generate'), [
                        'id' => 'generate-seo-url',
                        'class' => 'btn btn-in-input',
                        'url' => Url::to('/admin/shop/product/generate-seo-url')
                    ]); ?>
                </div>

                <?= $form->field($vendorTranslation, 'seoTitle', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->label(\Yii::t('shop', 'SEO title'))
                ?>
                <?= $form->field($vendorTranslation, 'seoDescription', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->textarea(['rows' => 3])->label(\Yii::t('shop', 'SEO description'))
                ?>
                <?= $form->field($vendorTranslation, 'seoKeywords', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->textarea(['rows' => 3])->label(\Yii::t('shop', 'SEO keywords'))
                ?>

                <!--SUBMIT-->
                <?= Html::submitButton(Yii::t('shop', 'Save'), [
                    'class' => 'btn btn-success pull-right'
                ]) ?>
            </div>

        </div>
        <?php $form->end(); ?>

    </div>
</div>
