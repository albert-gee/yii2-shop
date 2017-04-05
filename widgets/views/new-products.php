<?php
use xalberteinsteinx\shop\common\entities\Product;
use bl\multilang\entities\Language;
use yii\bootstrap\Html;
use yii\helpers\Url;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * @author Vyacheslav Nozhenko <vv.nojenko@gmail.com>
 *
 * @var \yii\web\View $this
 * @var Product[] $products
 * @var boolean $showOwners
 */


?>

<table class="table table-hover table-striped table-bordered">
    <thead>
    <tr>
        <th class="col-md-1 text-center"><?= Yii::t('shop', 'Photo'); ?></th>
        <th><?= Yii::t('shop', 'Title'); ?></th>
        <?php if ($showOwners): ?>
            <th><?= Yii::t('shop', 'Owner'); ?></th>
        <?php endif ?>
        <th><?= Yii::t('shop', 'Category'); ?></th>
        <th class="col-md-2"><?= Yii::t('shop', 'Created'); ?></th>
    </tr>
    </thead>
    <tbody>

    <tr>
        <td <?= ($showOwners) ? 'colspan="5"' : 'colspan="4"'; ?> class="text-center small">
            <?= Html::a('<i class="fa fa-plus m-r-xs"></i>' . Yii::t('shop', 'Add'), [
                '/shop/product/save', 'languageId' => Language::getCurrent()->id
            ]) ?>
        </td>
    </tr>

    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product) : ?>
            <?php $productImage = !empty($product->image->small) ? $product->image->small : ''; ?>

            <tr>
                <td class="text-center" style="padding: 5px;">
                    <a href="<?= Url::to(['/shop/product/add-image', 'id' => $product->id, 'languageId' => Language::getCurrent()->id]); ?>">
                        <?php if (!empty($product->image->small)): ?>
                            <div
                                style="background-image: url(<?= $productImage ?>); background-size: cover; background-position: center;
                                    width: 64px;
                                    height: 64px; ">
                            </div>
                        <?php else: ?>
                            <i class="fa fa-picture-o text-muted m-t-xs"></i>
                        <?php endif; ?>
                    </a>
                </td>
                <td style="vertical-align: middle;">
                    <?= Html::a(
                        $product->translation->title ?? '',
                        Url::toRoute(['/shop/product/save', 'id' => $product->id, 'languageId' => Language::getCurrent()->id])
                    ); ?>
                </td>
                <?php if ($showOwners): ?>
                    <td>
                        <?= (!empty($product->ownerProfile->name)) ? $product->ownerProfile->name : ''; ?>
                        <?= (!empty($product->ownerProfile->surname)) ? $product->ownerProfile->surname : ''; ?>
                    </td>
                <?php endif ?>
                <td style="vertical-align: middle;">
                    <?php if (!empty($product->category->translation->title)): ?>
                        <?= Html::a($product->category->translation->title, [
                            '/shop/category/save', 'id' => $product->category_id, 'languageId' => Language::getCurrent()->id
                        ]); ?>
                    <?php else: ?>
                        <span><?= Yii::t('shop', 'Without parent') ?></span>
                    <?php endif; ?>
                </td>
                <td style="vertical-align: middle;">
                    <?= Yii::$app->formatter->asRelativeTime($product->creation_time); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>