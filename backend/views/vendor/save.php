<?php
use marqu3s\summernote\Summernote;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use xalberteinsteinx\shop\common\entities\Vendor;
use xalberteinsteinx\shop\backend\components\form\VendorImage;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var Vendor $vendor
 * @var \xalberteinsteinx\shop\common\entities\VendorTranslation $vendorTranslation
 * @var VendorImage $vendor_image
 */

$this->title = Yii::t('shop', 'Save vendor');

//VendorAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-list"></i>
                <?= Html::encode($this->title); ?>

                <?= \xalberteinsteinx\shop\widgets\LanguageSwitcher::widget([
                    'selectedLanguage' => \bl\multilang\entities\Language::findOne($vendorTranslation->language_id)
                ]); ?>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                        'method' => 'post',
                        'options' => ['enctype' => 'multipart/form-data']
                    ]) ?>

                <div class="col-md-offset-2 col-md-8">
                    <!--TITLE-->
                    <?= $form->field($vendor, 'title')->textInput(['id' => 'producttranslation-title']) ?>

                    <!--IMAGE-->
                    <h4><?= Yii::t('shop', 'Logo'); ?></h4>
                    <div class="text-center">
                        <?php if(!empty($vendor->image_name)): ?>
                            <?= Html::img($vendor_image->getBig($vendor->image_name), [
                                'class' => 'img-thumbnail thumbnail center-block'
                            ]) ?>
                        <?php else: ?>
                            <div class="glyphicon glyphicon-picture text-muted" data-toggle="tooltip" data-placement="top"
                                 title="<?= Yii::t('shop', 'No image') ?>"
                                 data-original-title="<?= Yii::t('shop', 'No image') ?>"></div>
                        <?php endif; ?>
                    </div>

                    <!--IMAGE INPUT-->
                    <div class="row">
                        <div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4 text-center">
                            <?= $form->field($vendor_image, 'imageFile')->fileInput(); ?>
                        </div>
                    </div>

                    <!--DESCRIPTION-->
                    <?= $form->field($vendorTranslation, 'description', [
                        'inputOptions' => [
                            'class' => 'form-control'
                        ]
                    ])->widget(Summernote::className())->label(\Yii::t('shop', 'Description'));
                    ?>


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
                            'class' => 'btn btn-primary btn-generate',
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

                <?php $form->end(); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("$(\"[data-toggle='tooltip']\").tooltip();") ?>
