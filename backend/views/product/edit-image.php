<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $image \xalberteinsteinx\shop\common\entities\ProductImage
 * @var $imageTranslation \xalberteinsteinx\shop\common\entities\ProductImageTranslation
 * @var $selectedLanguage \bl\multilang\entities\Language
 */
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'action' => [
        'product/edit-image',
        'id' => $image->id,
        'languageId' => $selectedLanguage->id
    ],
    'method' => 'post',
    'options' => [
        'class' => 'tab-content',
        'data-pjax' => true
    ]
]);
?>

<?= $form->field($imageTranslation, 'alt')->textInput(); ?>

<?= \yii\bootstrap\Html::submitButton(); ?>

<?php $form->end(); ?>
