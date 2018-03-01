<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $vendors \sointula\shop\common\entities\Vendor
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
    <?php foreach ($vendors as $vendor): ?>
        <div class="col-md-4">
            <h1>
                <a href="<?= Url::to(['show', 'id' => $vendor->id]); ?>">
                    <?= $vendor->title; ?>
                </a>
            </h1>

            <?php if (!empty($vendor->image_name)): ?>
                <div class="vendor-image">
                    <?= Html::img($vendor->getImage('small')); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($vendor->translation)): ?>
                <div class="vendor-description">
                    <?= $vendor->translation->description; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
