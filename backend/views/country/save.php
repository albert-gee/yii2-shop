<?php

use xalberteinsteinx\shop\common\entities\ProductCountry;
use bl\multilang\entities\Language;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $country ProductCountry
 * @var $countryTranslation \xalberteinsteinx\shop\common\entities\ProductCountryTranslation
 * @var $countryImageModel \xalberteinsteinx\shop\backend\components\form\CountryImageForm
 * @var $languages Language[]
 * @var $selectedLanguage Language
 *
 */

$this->title = 'Edit country';
?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
    'options' => [
        'data-pjax' => true,
        'enctype' => 'multipart/form-data'
    ]
]);
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-heading">
                <i class="glyphicon glyphicon-list"></i>
                <?= \Yii::t('shop', 'Country') ?>
            </div>

            <div class="panel-body">
                <?php if (count($languages) > 1): ?>
                    <div class="dropdown">
                        <button class="btn btn-warning btn-xs dropdown-toggle" type="button" id="dropdownMenu1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <?= $selectedLanguage->name ?>
                            <span class="caret"></span>
                        </button>
                        <?php if (count($languages) > 1): ?>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                <?php foreach ($languages as $language): ?>
                                    <li>
                                        <a href="
                                            <?= Url::to([
                                            'save',
                                            'countryId' => $country->id,
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


                <div class="row">
                    <div class="col-md-5">
                        <?= $form->field($countryTranslation, 'title', [
                            'inputOptions' => [
                                'class' => 'form-control'
                            ]
                        ])->label('Title')
                        ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($countryImageModel, 'image', [
                            'inputOptions' => [
                                'class' => 'form-control'
                            ]
                        ])->widget(FileInput::classname(), [
                            'options' => ['accept' => 'image/*'],
                        ])
                        ?>
                        <?php if (!empty($country->image)): ?>
                        <div class="country-image">
                            <?= Html::img(Url::to('/images/shop-product-country/' . $country->image), ['style' => 'width: 100%;']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'btn btn-primary', 'style' => 'width: 100%']); ?>

                            <?php if (!empty($country->image)): ?>
                            <?= Html::a(
                                \Yii::t('shop', 'Remove image'),
                                Url::to(['/shop/country/remove-image', 'countryId' => $country->id]),
                                ['class' => 'btn btn-danger', 'style' => 'width: 100%']
                            ); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
