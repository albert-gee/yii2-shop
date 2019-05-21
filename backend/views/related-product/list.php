<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $product                \albertgeeca\shop\common\entities\Product
 * @var $products               \albertgeeca\shop\common\entities\Product[]
 * @var $relatedProducts        \albertgeeca\shop\common\entities\RelatedProduct[]
 * @var $newRelatedProduct      \albertgeeca\shop\common\entities\RelatedProduct
 * @var $selectedLanguage       \bl\multilang\entities\Language
 */
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = \Yii::t('shop', 'Related products');
?>

<!--Tabs-->
<?= $this->render('../product/_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $selectedLanguage
]); ?>

<div class="box padding20">

    <h1 class="text-center">
        <?= $this->title; ?>
    </h1>

    <p class="text-center">
        <?= Yii::t('shop', 'Related products are shown below description on product page.'); ?>
    </p>

    <div class="row">
        <div class="main col-md-8 block-center">

            <?php $form = \yii\widgets\ActiveForm::begin(); ?>

            <div class="row">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="col-md-10 text-center">
                            <?= \Yii::t('shop', 'Title'); ?>
                        </th>
                        <th class="col-md-2 text-center">
                            <?= \Yii::t('shop', 'Control'); ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <?= $form->field($newRelatedProduct, 'related_product_id')
                                ->widget(\yii2mod\chosen\ChosenSelect::className(), [
                                    'items' => ArrayHelper::map($products, 'id', 'translation.title'),
                                ]);
                            ?>
                        </td>
                        <td>
                            <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary']); ?>
                        </td>
                    </tr>
                    <?php if (!empty($relatedProducts)): ?>
                        <?php foreach ($relatedProducts as $relatedProduct): ?>
                            <tr>
                                <td>
                                    <?= $relatedProduct->relatedProduct->translation->title; ?>
                                </td>
                                <td class="text-center">
                                    <?= Html::a(
                                        '<span class="glyphicon glyphicon-remove"></span>',
                                        Url::to(['/shop/related-product/remove', 'id' => $relatedProduct->id]),
                                        ['class' => 'btn btn-danger btn-xs']);
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php $form::end(); ?>
        </div>
    </div>

</div>