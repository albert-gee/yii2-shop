<?php
use xalberteinsteinx\shop\backend\components\form\VendorImage;
use xalberteinsteinx\shop\common\entities\Vendor;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var Vendor[] $vendors
 * @var VendorImage $vendor_images
 */

$this->title = Yii::t('shop', 'Product vendors');
?>

<h1><?= Html::encode($this->title); ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-list"></i>
                <?= Yii::t('shop', 'Vendor list'); ?>
            </div>
            <div>
                <div class="panel-body">
                    <table class="table-bordered table-condensed table-hover">
                        <?php if (!empty($vendors)): ?>
                            <thead>
                            <tr>
                                <th class="col-xs-1 col-sm-1 col-md-1"><?= 'Id' ?></th>
                                <th class="col-xs-2 col-sm-2 col-md-2"><?= Yii::t('shop', 'Logo') ?></th>
                                <th class="col-xs-5 col-sm-7 col-md-7"><?= Yii::t('shop', 'Title') ?></th>
                                <th class="col-sm-2 col-md-2 text-center"><?= Yii::t('shop', 'Control') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($vendors as $vendor): ?>
                                <tr>
                                    <td class="text-muted">
                                        <?= $vendor->id ?>
                                    </td>

                                    <td>
                                        <div class="text-center">
                                            <?php if (!empty($vendor->image_name)): ?>
                                                <?= Html::img($vendor_images->getThumb($vendor->image_name), [
                                                        'class' => 'img-responsive',
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#' . $vendor->image_name
                                                ])?>
                                                <div id="<?= $vendor->image_name ?>" class="modal fade" role="dialog">
                                                    <?= Html::img($vendor_images->getBig($vendor->image_name), [
                                                            'class' => 'modal-dialog'
                                                    ])?>
                                                </div>
                                            <?php else: ?>
                                                <div class="glyphicon glyphicon-picture text-muted" data-toggle="tooltip" data-placement="top"
                                                     title="<?= Yii::t('shop', 'No image') ?>"
                                                     data-original-title="<?= Yii::t('shop', 'No image') ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?= $vendor->title ?>
                                    </td>

                                    <td class="text-center">
                                        <?= \xalberteinsteinx\shop\widgets\ManageButtons::widget([
                                            'model' => $vendor,
                                            'deleteUrl' =>  Url::to(['remove', 'id' => $vendor->id])
                                        ]); ?>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>
                    <div class="row-fluid" style="margin-top: 15px;">
                        <a href="<?= Url::to(['save']); ?>"
                           class="btn btn-primary pull-right">
                            <i class="fa fa-user-plus"></i> <?= Yii::t('shop', 'Add'); ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("$(\"[data-toggle='tooltip']\").tooltip();") ?>