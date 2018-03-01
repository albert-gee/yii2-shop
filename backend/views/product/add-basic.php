<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $prices                 array
 * @var $selectedLanguage       \bl\multilang\entities\Language
 * @var $product                \sointula\shop\common\entities\Product
 * @var $productTranslation     \sointula\shop\common\entities\ProductTranslation
 */
use rmrevin\yii\fontawesome\FA;
use sointula\shop\common\components\user\models\UserGroup;
use sointula\shop\common\entities\{
    PriceDiscountType, Product, ProductAvailability, ProductCountryTranslation, Vendor
};
use marqu3s\summernote\Summernote;
use yii\helpers\{
    ArrayHelper, Html, Url
};
use yii\widgets\ActiveForm;

$selectedLanguageId = $selectedLanguage->id;
?>

<!--Tabs-->
<?= $this->render('_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $selectedLanguage
]); ?>

<div class="box padding20">

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'enableClientValidation' => true,
        'action' => [
            'product/save',
            'id' => $product->id,
            'languageId' => $selectedLanguageId
        ]]);
    ?>

    <header>
        <section class="title">
            <h2><?= \Yii::t('shop', 'Basic options'); ?></h2>
        </section>

        <section class="buttons">
            <!--SAVE BUTTON-->
            <?= Html::submitButton(
                Html::tag('span', FA::i(FA::_SAVE) . ' ' . \Yii::t('shop', 'Save')),
                ['class' => 'btn btn-xs']); ?>

            <!--CANCEL BUTTON-->
            <?= Html::a(
                Html::tag('span', FA::i(FA::_STOP_CIRCLE) . ' ' . \Yii::t('shop', 'Cancel')),
                Url::to(['/shop/product']), [
                'class' => 'btn btn-danger btn-xs'
            ]); ?>

            <!--VIEW ON SITE-->
            <?php if (!empty($product->translation)) : ?>
                <?= Html::a(
                    Html::tag('span', FA::i(FA::_EXTERNAL_LINK) . Yii::t('shop', 'View on website')),
                    (Yii::$app->get('urlManagerFrontend'))->createAbsoluteUrl(['/shop/product/show', 'id' => $product->id], true), [
                    'class' => 'btn btn-info btn-xs',
                    'target' => '_blank'
                ]); ?>
            <?php endif; ?>

            <!--LANGUAGES-->
            <?= \sointula\shop\widgets\LanguageSwitcher::widget([
                'selectedLanguage' => $selectedLanguage,
            ]); ?>
        </section>
    </header>

    <!--BASIC-->
    <div id="basic">

        <!--MODERATION-->
        <?php if (Yii::$app->user->can('moderateProductCreation') && $product->status == Product::STATUS_ON_MODERATION) : ?>
            <h2><?= \Yii::t('shop', 'Moderation'); ?></h2>
            <p class="text-warning"><?= \Yii::t('shop', 'This product\'s status is "on moderation". You may accept or decline it.'); ?></p>
            <?= Html::a(\Yii::t('shop', 'Accept'), Url::toRoute(['change-product-status', 'id' => $product->id, 'status' => Product::STATUS_SUCCESS]), ['class' => 'btn btn-primary btn-xs']); ?>
            <?= Html::a(\Yii::t('shop', 'Decline'), Url::toRoute(['change-product-status', 'id' => $product->id, 'status' => Product::STATUS_DECLINED]), ['class' => 'btn btn-danger btn-xs']); ?>
            <hr>
        <?php endif; ?>

        <!--ERRORS-->
        <?php if ($product->hasErrors() || $productTranslation->hasErrors()): ?>
            <p class="text-danger">
                <?= \Yii::t('shop', 'Validation error. Please check all fields'); ?>
            </p>
        <?php endif; ?>


        <?php if (!\Yii::$app->user->can('createProductWithoutModeration') && $product->status == Product::STATUS_ON_MODERATION): ?>
            <p class="text-danger"><?= \Yii::t('shop', 'The product has not passed moderation yet'); ?></p>
        <?php endif; ?>

        <!--NAME-->
        <?= $form->field($productTranslation, 'title', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->label(\Yii::t('shop', 'Name'))
        ?>

        <div class="row">
            <div class="col-md-6">
                <div>
                    <!--SKU-->
                    <?php
                    $options = [
                        'inputOptions' => [
                            'class' => 'form-control',
                        ]
                    ]; ?>
                    <?= $field = $form->field($product, 'sku', $options)->label(\Yii::t('shop', 'SKU'))
                    ?>
                </div>
                <div>
                    <!--COUNTRY-->
                    <?= $form->field($product, 'country_id', $options)->dropDownList(
                        ['' => '-- no countries --'] +
                        ArrayHelper::map(ProductCountryTranslation::find()->where(['language_id' => $selectedLanguageId])->all(), 'country_id', 'title')
                    )->label(\Yii::t('shop', 'Country'));
                    ?>
                </div>
                <div>
                    <!--VENDOR-->
                    <?= $form->field($product, 'vendor_id', $options)->dropDownList(
                        ['' => '-- no vendor --'] +
                        ArrayHelper::map(Vendor::find()->all(), 'id', 'title')
                    )->label(\Yii::t('shop', 'Vendor'))
                    ?>
                </div>
                <div>
                    <!--AVAILABILITY-->
                    <?= $form->field($product, 'availability', $options)->dropDownList(
                        ArrayHelper::map(ProductAvailability::find()->all(), 'id', 'translation.title')
                    ); ?>
                </div>
                <div>
                    <!--NUMBER-->
                    <?= $form->field($product, 'number', $options)->textInput()
                    ?>
                </div>
            </div>

            <div class="col-md-6">
                <!--CATEGORY-->
                <label><?= \Yii::t('shop', 'Category'); ?></label>
                <?= \sointula\shop\widgets\InputTree::widget([
                    'className' => \sointula\shop\common\entities\Category::className(),
                    'form' => $form,
                    'model' => $product,
                    'attribute' => 'category_id',
                    'languageId' => $selectedLanguageId
                ]);
                ?>
            </div>
        </div>

        <div class="">
            <!--NEW-->
            <div style="display: inline-block;">
                <?= $form->field($product, 'new', [
                    'inputOptions' => [
                        'class' => '']
                ])->checkbox(); ?>
            </div>

            <!--SALE-->
            <div style="display: inline-block;">
                <?= $form->field($product, 'sale', [
                    'inputOptions' => [
                        'class' => '']
                ])->checkbox(); ?>
            </div>

            <!--POPULAR-->
            <div style="display: inline-block;">
                <?= $form->field($product, 'popular', [
                    'inputOptions' => [
                        'class' => '']
                ])->checkbox(); ?>
            </div>

            <!--SHOW-->
            <div style="display: inline-block;">
                <?php $product->show = ($product->isNewRecord) ? true : $product->show; ?>
                <?= $form->field($product, 'show', [
                    'inputOptions' => [
                        'class' => '']
                ])->checkbox(); ?>
            </div>
        </div>

        <!--BASE PRICE-->
        <?php if (!empty($prices)): ?>
            <hr>
            <h2><?= \Yii::t('shop', 'Base price'); ?></h2>
            <?php foreach ($prices as $key => $price) : ?>
                <h3 class="text-center"><?= UserGroup::findOne($key)->translation->title; ?></h3>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($price, "[$key]price", $options)->textInput(['type' => 'number', 'step' => '0.01']); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($price, "[$key]discount_type_id")
                            ->dropDownList(
                                ['' => '--none--'] +
                                ArrayHelper::map(PriceDiscountType::find()->asArray()->all(), 'id', 'title'));
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($price, "[$key]discount")->textInput(['type' => 'number', 'step' => '0.01']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <hr>
        <h2><?= \Yii::t('shop', 'Product description'); ?></h2>
        <!--SHORT DESCRIPTION-->
        <?= $form->field($productTranslation, 'description', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->widget(Summernote::className())->label(\Yii::t('shop', 'Short description'));
        ?>

        <!--FULL TEXT-->
        <?= $form->field($productTranslation, 'full_text', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->widget(Summernote::className())->label(\Yii::t('shop', 'Full description'));
        ?>

        <!--SEO-->
        <hr>
        <h2><?= \Yii::t('shop', 'SEO options'); ?></h2>
        <div class="seo-url">
            <?= $form->field($productTranslation, 'seoUrl', [
                'inputOptions' => [
                    'class' => 'form-control'
                ],
            ])->label('SEO URL')
            ?>
            <?= Html::button(\Yii::t('shop', 'Generate'), [
                'id' => 'generate-seo-url',
                'class' => 'btn btn-primary btn-in-input',
                'url' => Url::to('generate-seo-url')
            ]); ?>
        </div>

        <?= $form->field($productTranslation, 'seoTitle', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->label(\Yii::t('shop', 'SEO title'))
        ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($productTranslation, 'seoDescription', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->textarea(['rows' => 3])->label(\Yii::t('shop', 'SEO description'))
                ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($productTranslation, 'seoKeywords', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->textarea(['rows' => 3])->label(\Yii::t('shop', 'SEO keywords'))
                ?>
            </div>
        </div>

        <section class="buttons">
            <!--SAVE BUTTON-->
            <?= Html::submitButton(FA::i(FA::_SAVE) . ' ' . \Yii::t('shop', 'Save'), ['class' => 'btn btn-xs']); ?>

            <!--CANCEL BUTTON-->
            <?= Html::a(FA::i(FA::_STOP_CIRCLE) . ' ' . \Yii::t('shop', 'Cancel'), Url::to(['/shop/product']), [
                'class' => 'btn btn-danger btn-xs'
            ]); ?>
        </section>
    </div>

    <?php $form::end(); ?>

    <?php if (!$product->isNewRecord): ?>
        <div class="created-by">
            <p>
                <b>
                    <?= \Yii::t('shop', 'Created by'); ?>:
                </b>
                <?= $product->ownerProfile->user->email ?? ''; // TODO: update this    ?>
            </p>
            <p>
                <b>
                    <?= \Yii::t('shop', 'Created at'); ?>:
                </b>
                <?= $product->creation_time; ?>

            </p>
            <p>
                <b>
                    <?= \Yii::t('shop', 'Shows'); ?>:
                </b>
                <?= $product->shows; ?>

            </p>
        </div>
    <?php endif; ?>

</div>
