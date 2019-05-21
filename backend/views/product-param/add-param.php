<?php
use albertgeeca\shop\common\entities\Param;
use albertgeeca\shop\common\entities\ParamTranslation;
use bl\multilang\entities\Language;
use yii\widgets\Pjax;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $params             Param[]
 * @var $param_translation  ParamTranslation
 * @var $productId          integer
 * @var $selectedLanguage   Language
 * @var $languageIndex      integer
 */

$languageId = $selectedLanguage->id;
?>

<!--Tabs-->
<?= $this->render('../product/_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $selectedLanguage
]); ?>

<div class="box padding20">

    <h2><?= \Yii::t('shop', 'Params'); ?></h2>

    <?php Pjax::begin([
        'id' => 'p-product-param-' . $productId,
        'enablePushState' => false,
        'enableReplaceState' => false,
        'formSelector' => '#product-param-' . $productId,
        'submitEvent' => 'change-params-table',
//        'linkSelector' => '.pproductparams'
    ]); ?>

    <?= $this->render('_params-table', [
            'modifiedElementId' => null,
            'productId' => $productId,
            'languageId' => $languageId,
            'param_translation' => $param_translation,
            'params' => $params]); ?>

    <?php Pjax::end(); ?>

</div>