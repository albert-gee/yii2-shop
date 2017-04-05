<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $additionalProductsCategories \xalberteinsteinx\shop\common\entities\Category
 * @var $productAdditionalProducts \xalberteinsteinx\shop\common\entities\Product
 * @var $productId integer
 */
use yii\helpers\Url;

?>

<h1>
    <?= \Yii::t('shop', 'Additional products'); ?>
</h1>


<h2 class="text-center"><?= \Yii::t('shop', 'Additional products list'); ?></h2>
<div class="row">
    <div class="col-md-8 col-md-offset-2">

        <div class="row">
            <label for="additional-product-selector"><?= \Yii::t('shop', 'Additional products'); ?></label>
            <select name="" id="additional-product-selector" class="form-control col-md-6" data-product-id="<?= $productId; ?>">
                <option value=""></option>
                <?php foreach ($additionalProductsCategories as $category) : ?>
                    <optgroup label="<?= $category->translation->title; ?>">
                        <?php foreach ($category->products as $product) : ?>
                            <option value="<?= $product->id; ?>"><?= $product->translation->title; ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endforeach; ?>
            </select>
        </div>

        <hr>

        <table class="table table-hover" id="additional-products-table">
            <tr>
                <th>
                    <?= \Yii::t('shop', 'Title'); ?>
                </th>
                <th class="col-md-2">
                    <?= Yii::t('shop', 'Control'); ?>
                </th>
            </tr>
            <?php foreach ($productAdditionalProducts as $productAdditionalProduct) : ?>
                <tr>
                    <td>
                        <?= $productAdditionalProduct->additionalProduct->translation->title; ?>
                    </td>
                    <td>
                        <a href="<?= Url::to(['/shop/additional-product/remove-additional-product', 'id' => $productAdditionalProduct->id]); ?>"
                           class="btn btn-danger btn-xs remove-additional-product">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

