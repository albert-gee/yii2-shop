<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $product            \sointula\shop\common\entities\Product
 * @var $selectedLanguage   \bl\multilang\entities\Language
 */
use yii\helpers\Html;
use yii\helpers\Url;

$newProductMessage = Yii::t('shop', 'You must save new product before this action');
$selectedLanguageId = $selectedLanguage->id;
?>

<header class="tabs">
    <ul>

        <!--BASIC-->
        <li class="<?= Yii::$app->controller->action->id == 'save' ? 'active' : ''; ?>">
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
                        'class' => 'pjax'
                    ]); ?>
        </li>

        <!--VIDEO-->
        <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-video' ? 'active' : ''; ?>">
            <?= ($product->isNewRecord) ?
                Html::a(\Yii::t('shop', 'Video'), null, [
                    'data-toggle' => 'tooltip',
                    'title' => $newProductMessage
                ]) :
                Html::a(\Yii::t('shop', 'Video'), Url::to(['/shop/product/add-video', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                    [
                        'aria-expanded' => 'true',
                        'class' => 'pjax'
                    ]); ?>
        </li>

        <!--PARAMS-->
        <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-param' ? 'active' : ''; ?>">
            <?= ($product->isNewRecord) ?
                Html::a(\Yii::t('shop', 'Params'), null, [
                    'data-toggle' => 'tooltip',
                    'title' => $newProductMessage
                ]) :
                Html::a(\Yii::t('shop', 'Params'), Url::to(['/shop/product-param/add-param', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                    [
                        'aria-expanded' => 'true',
                        'class' => 'pjax'
                    ]); ?>
        </li>

        <!--FILES-->
        <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-file' ? 'active' : ''; ?>">
            <?= ($product->isNewRecord) ?
                Html::a(\Yii::t('shop', 'Files'), null, [
                    'data-toggle' => 'tooltip',
                    'title' => $newProductMessage
                ]) :
                Html::a(\Yii::t('shop', 'Files'), Url::to(['/shop/product-file/add-file', 'id' => $product->id, 'languageId' => $selectedLanguageId]),
                    [
                        'aria-expanded' => 'true',
                        'class' => 'pjax'
                    ]); ?>
        </li>

        <!--COMBINATIONS-->
        <?php if (\Yii::$app->getModule('shop')->enableCombinations): ?>
            <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-combination' ? 'active' : ''; ?>">
                <?= ($product->isNewRecord) ?
                    Html::a(\Yii::t('shop', 'Combinations'), null, [
                        'data-toggle' => 'tooltip',
                        'title' => $newProductMessage
                    ]) :
                    Html::a(\Yii::t('shop', 'Combinations'), Url::to(['/shop/combination/add-combination', 'productId' => $product->id, 'languageId' => $selectedLanguageId]),
                        [
                            'aria-expanded' => 'true',
                        ]); ?>
            </li>
        <?php endif; ?>

        <!--ADDITIONAL PRODUCTS-->
        <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'add-additional' ? 'active' : ''; ?>">
            <?= ($product->isNewRecord) ?
                Html::a(\Yii::t('shop', 'Additional products'), null, [
                    'data-toggle' => 'tooltip',
                    'title' => $newProductMessage
                ]) :
                Html::a(\Yii::t('shop', 'Additional products'), Url::to(['/shop/additional-product/add-additional', 'productId' => $product->id, 'languageId' => $selectedLanguageId]),
                    [
                        'aria-expanded' => 'true',
                        'class' => 'pjax'
                    ]); ?>
        </li>

        <!--RELATED PRODUCTS-->
        <li class="<?= (empty($product->translation)) ? 'disabled' : ''; ?> <?= Yii::$app->controller->action->id == 'list' ? 'active' : ''; ?>">
            <?= ($product->isNewRecord) ?
                Html::a(\Yii::t('shop', 'Related products'), null, [
                    'data-toggle' => 'tooltip',
                    'title' => $newProductMessage
                ]) :
                Html::a(\Yii::t('shop', 'Related products'), Url::to(['/shop/related-product/list', 'productId' => $product->id, 'languageId' => $selectedLanguageId]),
                    [
                        'aria-expanded' => 'true',
                        'class' => 'pjax'
                    ]); ?>
        </li>
    </ul>
</header>
