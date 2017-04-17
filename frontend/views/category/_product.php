<?php
/**
 * @var Product $model
 */
use xalberteinsteinx\shop\frontend\components\forms\CartForm;
use xalberteinsteinx\shop\common\entities\Product;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$modelUrl = Url::to(['/shop/product/show',
    'id' => $model->id
]);
?>

<div class="thumbnail">
    <span>
        <mark>
            <?= Yii::t('shop', 'Art. {sku}', [
                'sku' => $model->sku
            ]) ?>
        </mark>
    </span>

    <?php if (!empty($model->image->small)): ?>
        <a href="<?= $modelUrl ?>">
            <?= Html::img($model->image->small, [
                'alt' => (!empty($model->image->translation->alt)) ? $model->image->translation->alt : ''
            ]) ?>
        </a>
    <?php endif ?>

    <div class="caption">
        <a href="<?= $modelUrl ?>">
            <p class="h4"><?= StringHelper::truncate($model->translation->title, 40) ?></p>
        </a>

        <small class="text-muted"><?= StringHelper::truncate(strip_tags($model->translation->description), 130); ?></small>

        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'action' => ['/shop/cart/add'],
                    'options' => [
                        'class' => 'col-md-9 row'
                    ]]);
                $cart = new CartForm();
                ?>
                <?= $form->field($cart, 'productId', [
                    'template' => '{input}',
                    'options' => []
                ])
                    ->hiddenInput(['value' => $model->id])
                    ->label(false);
                ?>

                <?= $form->field($cart, 'count', [
                    'template' => '{input}',
                    'options' => []
                ])
                    ->hiddenInput(['value' => 1])
                    ->label(false);
                ?>

                <div class="help-block"></div>

                <!--PRICE-->
                <?php if (!empty($model->price)): ?>
                    <small><?= Yii::t('shop', 'Price'); ?>:</small>
                    <strong><?= Yii::$app->formatter->asCurrency($model->getDiscountPrice()); ?></strong>
                    <?php if (!empty($model->discount_type_id)): ?>
                        <strike><?= Yii::$app->formatter->asCurrency($model->getOldPrice()); ?></strike>
                    <?php endif ?>
                <?php endif ?>

                <div class="help-block"></div>

                <button type="submit" class="btn btn-success">
                    <i class="glyphicon glyphicon-shopping-cart"></i>
                    <?= Yii::t('shop', 'Add to cart'); ?>
                </button>
                <?php $form->end() ?>

                <?php if (!Yii::$app->user->isGuest && !$model->isFavorite()): ?>
                    <?php $addFavoriteProductUrl = Url::to(['/shop/favorite-product/add', 'productId' => $model->id]); ?>
                    <a href="<?= $addFavoriteProductUrl ?>" class="btn btn-sm btn-warning pull-right">
                        <i class="glyphicon glyphicon-star"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>