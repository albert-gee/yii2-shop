<?php
use rmrevin\yii\fontawesome\FA;
use sointula\shop\backend\components\form\VendorImage;
use sointula\shop\common\entities\Vendor;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var Vendor[] $vendors
 * @var VendorImage $vendor_images
 */

$this->title = Yii::t('shop', 'Vendor list');
?>

<div class="box">

    <div class="box-title">
        <h1><?= FA::i(FA::_SHIELD) . ' ' . Html::encode($this->title); ?></h1>
        <a href="<?= Url::to(['save']); ?>" class="btn btn-xs">
            <?= FA::i(FA::_USER_PLUS) . ' ' . Yii::t('shop', 'Add'); ?>
        </a>
    </div>

    <div class="box-content">
        <table class="table">
            <?php if (!empty($vendors)): ?>
                <thead>
                <tr>
                    <th class="col-md-1"><?= 'Id' ?></th>
                    <th class="col-md-2"><?= Yii::t('shop', 'Logo') ?></th>
                    <th class="col-md-7"><?= Yii::t('shop', 'Title') ?></th>
                    <th class="col-md-2 text-center"><?= Yii::t('shop', 'Control') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td>
                            <?= $vendor->id ?>
                        </td>

                        <td>
                            <?php if (!empty($vendor->image_name)): ?>
                                <?= Html::img($vendor_images->getSmall($vendor->image_name))?>
                            <?php else: ?>
                                <?= FA::i(FA::_IMAGE); ?>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= $vendor->title ?>
                        </td>

                        <td class="text-center">
                            <?= \sointula\shop\widgets\ManageButtons::widget([
                                'model' => $vendor,
                                'deleteUrl' =>  Url::to(['remove', 'id' => $vendor->id])
                            ]); ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
        </table>
    </div>
</div>