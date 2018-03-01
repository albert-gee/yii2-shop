<?php

use rmrevin\yii\fontawesome\FA;
use sointula\shop\common\entities\ProductCountry;
use bl\multilang\entities\Language;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $country ProductCountry
 * @var $countryTranslation \sointula\shop\common\entities\ProductCountryTranslation
 * @var $countryImageModel \sointula\shop\backend\components\form\CountryImageForm
 * @var $languages Language[]
 * @var $selectedLanguage Language
 *
 */

$this->title = Yii::t('shop', ($country->isNewRecord) ? 'Add country' : 'Edit country');
?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
    'options' => [
        'data-pjax' => true,
        'enctype' => 'multipart/form-data'
    ]
]);
?>

<div class="box">
    <div class="box-title">
        <h1>
            <?= FA::i(FA::_FLAG) . ' ' . \Yii::t('shop', 'Country') ?>
        </h1>

        <!--LANGUAGES-->
        <?= \sointula\shop\widgets\LanguageSwitcher::widget([
            'selectedLanguage' => $selectedLanguage,
        ]); ?>
    </div>

    <div class="box-content">

        <!--TITLE-->
        <?= $form->field($countryTranslation, 'title', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->label('Title')
        ?>

        <!--IMAGE-->
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($countryImageModel, 'image', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->fileInput(); ?>
            </div>

            <div class="col-md-6">
                <?php if (!empty($country->image)): ?>
                    <div class="country-image">
                        <?= Html::img(Url::to('/images/shop-product-country/' . $country->image), ['style' => 'width: 100%;']); ?>
                    </div>

                    <?= Html::a(
                        \Yii::t('shop', 'Remove image'),
                        Url::to(['/shop/country/remove-image', 'countryId' => $country->id]),
                        ['class' => 'btn btn-danger', 'style' => 'width: 100%']
                    ); ?>
                <?php endif; ?>
            </div>
        </div>

        <?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'btn']); ?>

    </div>
</div>

<?php ActiveForm::end(); ?>
