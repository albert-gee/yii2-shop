<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $additionalProductsCategories   \xalberteinsteinx\shop\common\entities\Category
 * @var $productAdditionalProducts      \xalberteinsteinx\shop\common\entities\Product
 * @var $product                        \xalberteinsteinx\shop\common\entities\Product
 * @var $selectedLanguage               \bl\multilang\entities\Language
 */

use yii\helpers\Url;
use yii\widgets\Pjax;

$productId = $product->id;
?>

<!--Tabs-->
<?= $this->render('../product/_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $selectedLanguage
]); ?>

<div class="box padding20">

    <?php Pjax::begin([
        'enablePushState' => false
    ]); ?>

    <h1 class="text-center">
        <?= \Yii::t('shop', 'Additional products'); ?>
    </h1>


    <div class="row">
        <div class="col-md-8 block-center">

            <div class="row">
                <select name="" id="additional-product-selector" class="form-control col-md-6"
                        data-product-id="<?= $productId; ?>">
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

</div>