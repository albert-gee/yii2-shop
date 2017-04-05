<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $product \xalberteinsteinx\shop\common\entities\Product
 * @var $selectedLanguageId integer
 */
use xalberteinsteinx\shop\subsite\models\entities\ShopEntityQueen;
use xalberteinsteinx\shop\subsite\models\ProductModel;
use yii\helpers\Html;
use yii\helpers\Url;

$newProductMessage = Yii::t('shop', 'You must save new product before this action');

$dis = false;
if(Yii::$app->user->can('subSiteAdmin')) {
    if(!empty(ShopEntityQueen::findQueenId(ProductModel::$entityName, $product->id))) {
        $dis = true;
    }
}

?>

<ul class="nav nav-tabs">

    <!--BASIC-->
    <li class="<?= Yii::$app->controller->action->id == 'save' ? 'tab active' : 'tab'; ?>">
        <?= Html::a(\Yii::t('shop', 'Basic'), Url::to([
            '/shop/product/save', 'id' => $product->id, 'languageId' => $selectedLanguageId
        ]),
            [
                'aria-expanded' => 'true',
            ]);
        ?>
    </li>

    <!--PHOTO-->
    <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-image' ? 'active' : ''; ?>">

        <?= ($product->isNewRecord) ?
            Html::a(\Yii::t('shop', 'Photo'), null, [
                'data-toggle' => 'tooltip',
                'title' => $newProductMessage
            ]) :
            Html::a(\Yii::t('shop', 'Photo'), Url::to(['/shop/product/add-image', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                [
                    'aria-expanded' => 'true',
                ]); ?>
    </li>

    <!--VIDEO-->
    <li class="<?= (empty($product->translation) || $dis) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-video' ? 'tab active' : 'tab'; ?>">
        <?= ($product->isNewRecord) || $dis ?
            Html::a(\Yii::t('shop', 'Video'), null, [
                'data-toggle' => 'tooltip',
                'title' => $newProductMessage
            ]) :
            Html::a(\Yii::t('shop', 'Video'), Url::to(['/shop/product/add-video', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                [
                    'aria-expanded' => 'true'
                ]); ?>
    </li>

    <!--PARAMS-->
    <li class="<?= (empty($product->translation) || $dis) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-param' ? 'tab active' : 'tab'; ?>">
        <?= ($product->isNewRecord) || $dis ?
            Html::a(\Yii::t('shop', 'Params'), null, [
                'data-toggle' => 'tooltip',
                'title' => $newProductMessage
            ]) :
            Html::a(\Yii::t('shop', 'Params'), Url::to(['/shop/product-param/add-param', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                [
                    'aria-expanded' => 'true'
                ]); ?>
    </li>

    <!--FILES-->
    <li class="<?= (empty($product->translation) || $dis) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-file' ? 'tab active' : 'tab'; ?>">
        <?= ($product->isNewRecord) || $dis ?
            Html::a(\Yii::t('shop', 'Files'), null, [
                'data-toggle' => 'tooltip',
                'title' => $newProductMessage
            ]) :
            Html::a(\Yii::t('shop', 'Files'), Url::to(['/shop/product-file/add-file', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                [
                    'aria-expanded' => 'true'
                ]); ?>
    </li>

    <!--COMBINATIONS-->
    <?php if (\Yii::$app->getModule('shop')->enableCombinations): ?>
        <li class="<?= (empty($product->translation) || $dis) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-combination' ? 'tab active' : 'tab'; ?>">
            <?= ($product->isNewRecord) || $dis ?
                Html::a(\Yii::t('shop', 'Combinations'), null, [
                    'data-toggle' => 'tooltip',
                    'title' => $newProductMessage
                ]) :
                Html::a(\Yii::t('shop', 'Combinations'), Url::to(['/shop/combination/add-combination', 'productId' => $product->id, 'languageId' => $selectedLanguageId]),
                    [
                        'aria-expanded' => 'true'
                    ]); ?>
        </li>
    <?php endif; ?>

    <!--ADDITIONAL PRODUCTS-->
    <li class="<?= (empty($product->translation) || $dis) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-additional' ? 'tab active' : 'tab'; ?>">
        <?= ($product->isNewRecord) || $dis ?
            Html::a(\Yii::t('shop', 'Additional products'), null, [
                'data-toggle' => 'tooltip',
                'title' => $newProductMessage
            ]) :
            Html::a(\Yii::t('shop', 'Additional products'), Url::to(['/shop/additional-product/add-additional', 'productId' => $product->id, 'languageId' => $selectedLanguageId]),
                [
                    'aria-expanded' => 'true'
                ]); ?>
    </li>

    <!--RELATED PRODUCTS-->
    <li class="<?= (empty($product->translation) || $dis) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'list' ? 'tab active' : 'tab'; ?>">
        <?= ($product->isNewRecord) || $dis ?
            Html::a(\Yii::t('shop', 'Related products'), null, [
                'data-toggle' => 'tooltip',
                'title' => $newProductMessage
            ]) :
            Html::a(\Yii::t('shop', 'Related products'), Url::to(['/shop/related-product/list', 'productId' => $product->id, 'languageId' => $selectedLanguageId]),
                [
                    'aria-expanded' => 'true'
                ]); ?>
    </li>
</ul>
