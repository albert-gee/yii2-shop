<?php
use xalberteinsteinx\shop\frontend\components\forms\CartForm;
use xalberteinsteinx\shop\common\entities\Category;
use xalberteinsteinx\shop\common\entities\Combination;
use xalberteinsteinx\shop\common\entities\Param;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\common\entities\ProductCountry;
use xalberteinsteinx\shop\frontend\assets\ProductAsset;
use xalberteinsteinx\shop\widgets\assets\RecommendedProductsAsset;
use xalberteinsteinx\shop\widgets\RecommendedProducts;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var Product $product
 * @var CartForm $cart
 * @var Combination $defaultCombination
 */
RecommendedProductsAsset::register($this);
ProductAsset::register($this);
?>

<!--BREADCRUMBS-->
<div>
    <?php echo Breadcrumbs::widget([
        'itemTemplate' => "<li><b><span>{link}</span></b></li>\n",
        'homeLink' => [
            'label' => Yii::t('frontend/navigation', 'Главная'),
            'url' => Url::toRoute(['/']),
            'itemprop' => 'url',
        ],
        'links' => [
            [
                'label' => Yii::t('frontend/navigation', 'Магазин'),
                'url' => Url::toRoute(['/shop']),
                'itemprop' => 'url',
            ],
            [
                'label' => (!empty($product->category->translation->title)) ? $product->category->translation->title : '',
                'url' => (!empty($product->category)) ? Url::toRoute(['category/show', 'id' => $product->category->id]) : '',
                'itemprop' => 'url',
            ],
            $product->translation->title,
        ],
    ]);
    ?>
</div>

<!--ALERT-->
<div class="col-lg-12">
    <?php if (Yii::$app->session->hasFlash('alert')): ?>
        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('alert') ?>
        </div>
    <?php endif; ?>
</div>

<!--PRODUCT CARD-->
<div class="product-page">

    <!--TITLE-->
    <h1 class="col-md-12 text-center"><?= (!empty($product->translation->title))
            ? $product->translation->title : '' ?>
    </h1>

    <div class="row">
        <!--IMAGE-->
        <div class="col-md-4 image">
            <?= Html::img(
                (!empty($product->images)) ? $product->image->thumb : Url::toRoute('/images/default.jpg'),
                [
                    'class' => 'media-object img-responsive',
                    'alt' => (!empty($product->image->translation->alt)) ? Html::encode($product->image->translation->alt) : ''
                ]); ?>
        </div>


        <div class="col-md-8">
            <!--SKU-->
            <?php if (!empty($product->sku)) : ?>
                <div class="intro-text">
                    <p>
                        <strong><?= \Yii::t('shop', 'SKU'); ?></strong>: <?= $product->sku; ?>
                    </p>
                </div>
            <?php endif ?>

            <!--VENDOR-->
            <?php if (!empty($product->vendor)) : ?>
                <div class="intro-text">
                    <p>
                        <strong><?= \Yii::t('shop', 'Vendor'); ?></strong>: <?= $product->vendor->title; ?>
                    </p>
                </div>
            <?php endif ?>

            <!--COUNTRY-->
            <?php if (!empty($product->productCountry)) : ?>
                <p>
                    <strong><?= \Yii::t('shop', 'Country'); ?></strong>: <?= $product->productCountry->translation->title; ?>
                </p>
            <?php endif; ?>

            <!--AVAILABILITY-->
            <?php if (!empty($product->availability)) : ?>
                <div class="availability">
                    <p class="">
                        <strong><?= $product->productAvailability->translation->title; ?></strong>
                    </p>
                    <p>
                        <?= $product->productAvailability->translation->description; ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- DESCRIPTION -->
            <p class="article-label"><?= Yii::t('shop', 'Description'); ?></p>
            <?php if (!empty($product->translation->description)) : ?>
                <div class="description">
                    <?= $product->translation->description ?>
                </div>
            <?php endif ?>

            <?php if (!empty($product->files)) : ?>
                <h4><?= Yii::t('shop', 'Files'); ?>:</h4>
                <div class="list-group">
                    <?php foreach ($product->files as $file): ?>
                        <a href="<?= $file->getFile() ?>" class="list-group-item" target="_blank">
                            <h4 class="list-group-item-heading">
                                <i class="glyphicon glyphicon-file"></i>
                                <?= $file->file ?>
                            </h4>
                            <b><?= $file->translation->type ?></b>
                            <p class="list-group-item-text"><?= $file->translation->description ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin([
                        'method' => 'post',
                        'action' => ['/shop/cart/add']
                    ]); ?>

                    <!--PRICES-->
                    <?= \xalberteinsteinx\shop\frontend\widgets\ProductPrices::widget([
                        'product' => $product,
                        'form' => $form,
                        'defaultCombination' => $defaultCombination
                    ]); ?>


                    <!--ADD TO FAVORITE-->
                    <?php if (!Yii::$app->user->isGuest) : ?>
                        <?php if (!$product->isFavorite()) : ?>
                            <?= Html::a(
                                Yii::t('shop', 'Add to favorites'),
                                Url::to(['/shop/favorite-product/add', 'productId' => $product->id]),
                                ['class' => 'btn btn-info']
                            ); ?>
                        <?php else : ?>
                            <?= Html::a(
                                Yii::t('shop', 'Remove from favorites'),
                                Url::to(['/shop/favorite-product/remove', 'productId' => $product->id]),
                                ['class' => 'btn btn-warning']
                            ); ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php $form::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <!--FULL TEXT -->
    <?php if (!empty($product->translation->full_text)) : ?>
        <div class="full-text">
            <?= $product->translation->full_text ?>
        </div>
    <?php endif ?>

    <!--PARAMS-->
    <?php if (!empty($product->params)) : ?>
        <h4 class="text-center"><?= Yii::t('shop', 'Params'); ?>:</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                <?php foreach ($product->params as $param): ?>
                    <tr>
                        <td class="text-right text-uppercase col-md-4"><?= $param->translation->name; ?></td>
                        <td class="col-md-8"><?= $param->translation->value; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<!--RECOMMENDED PRODUCTS-->
<div class="row">
    <?= RecommendedProducts::widget([
        'id' => $product->id,
    ]); ?>
</div>