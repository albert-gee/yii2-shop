<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $vendor \albertgeeca\shop\common\entities\Vendor
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="vendor">
    <h1 class="text-center">
        <a href="<?= Url::to(['show', 'id' => $vendor->id]); ?>">
            <?= $vendor->title; ?>
        </a>
    </h1>

    <?php if (!empty($vendor->image_name)): ?>
        <div class="vendor-image text-center">
            <?= Html::img($vendor->getImage('big')); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($vendor->translation)): ?>
        <div class="vendor-description">
            <?= $vendor->translation->description; ?>
        </div>
    <?php endif; ?>

    <div class="vendor-products row">
        <?php foreach ($vendor->moderatedProducts as $product): ?>
            <div class="col-md-3">
                <h2>
                    <a href="<?= Url::to(['/shop/product/show', 'id' => $product->id]); ?>">
                        <?= $product->translation->title; ?>
                    </a>
                </h2>
                <?php if ((!empty($product->image))): ?>
                    <div class="product-image">
                        <?= Html::img($product->image->small); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
