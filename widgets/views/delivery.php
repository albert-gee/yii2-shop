<?php
use sointula\shop\widgets\assets\DeliveryAsset;
use bl\multilang\entities\Language;
use yii\helpers\ArrayHelper;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $deliveryMethods \sointula\shop\common\entities\DeliveryMethod[]
 * @var $form \yii\bootstrap\ActiveForm
 * @var $model \yii\base\Model
 */

DeliveryAsset::register($this);

$languagePrefix = (Language::getCurrent()->lang_id != Language::getDefault()->lang_id) ? '/' . Language::getCurrent()->lang_id : '';
?>

<div id="delivery-methods" data-language-prefix="<?=$languagePrefix; ?>">
    <h3><?= Yii::t('cart', 'Select delivery method'); ?>:</h3>

    <div class="row">

        <?= $form->field($model, 'delivery_id')
            ->radioList(ArrayHelper::map($deliveryMethods, 'id',
                function ($item) {
                    return $item->translation->title;
                }))
            ->label(false); ?>

        <div class="col-md-12 delivery-info">
            <div class="col-md-3">
                <img id="delivery-logo" src="" alt="">
            </div>
            <div class="col-md-9">
                <p id="delivery-title"></p>
                <p id="delivery-description"></p>

                <div class="post-office">

<!--                    --><?//= \bl\cms\novaposhta\widgets\NovaPoshtaWarehouseSelector::widget([
//                        'language' => (\Yii::$app->language == 'ru') ? 'ru' : 'ua',
//                        'form' => $form,
//                        'formModel' => $model,
//                        'formAttribute' => 'postoffice'
//                    ]); ?>
                </div>

                <!--ADDRESS-->
                <div class="address">
                    <h2 class="text-center"><?= Yii::t('cart', 'Адрес'); ?></h2>
                    <?= $form->field($address, 'city')->textInput()->label(Yii::t('cart', 'City')); ?>
                    <?= $form->field($address, 'street')->textInput()->label(Yii::t('cart', 'Street')); ?>
                    <?= $form->field($address, 'house')->textInput()->label(Yii::t('cart', 'House')); ?>
                    <?= $form->field($address, 'apartment')->textInput()->label(Yii::t('cart', 'Apartment')); ?>
                </div>
            </div>
        </div>
    </div>
</div>



