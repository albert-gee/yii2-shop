<?php
use yii\helpers\{
    Html, Url
};
use bl\multilang\entities\Language;
use bl\multilang\MultiLangUrlManager;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\backend\assets\EditProductAsset;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var \yii\web\View $this
 * @var Product $product
 * @var Language $selectedLanguage
 * @var MultiLangUrlManager $urlManagerFrontend
 * @var string $viewName
 * @var array $params
 */

EditProductAsset::register($this);

$this->title = \Yii::t('shop', ($product->isNewRecord) ? 'Creating a new product' : 'Changing the product');
$urlManagerFrontend = Yii::$app->get('urlManagerFrontend');

$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    [
        'label' => Yii::t('shop', 'Products'),
        'url' => ['/shop/product'],
        'itemprop' => 'url'
    ]
];
$this->params['breadcrumbs'][] = (!empty($product->translation)) ? $product->translation->title :
    \Yii::t('shop', 'New product');
?>

<!--BODY PANEL-->
<div class="tabs-container">

    <?= $this->render('_product-tabs', [
        'product' => $product,
        'selectedLanguageId' => $selectedLanguage->id
    ]); ?>

    <div class="box-content ">

        <!--VIEW ON SITE-->
        <?php if (!empty($product->translation)) : ?>
            <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-external-link']) . Html::tag('span', Yii::t('shop', 'View on website')),
                $urlManagerFrontend->createAbsoluteUrl(['/shop/product/show', 'id' => $product->id], true), [
                    'class' => 'btn btn-info btn-xs pull-right m-t-xs m-l-xs',
                    'target' => '_blank'
                ]); ?>
        <?php endif; ?>

        <!--LANGUAGES-->
        <?= \xalberteinsteinx\shop\widgets\LanguageSwitcher::widget([
            'selectedLanguage' => $selectedLanguage,
        ]); ?>

        <!--CANCEL BUTTON-->
        <?= Html::a(\Yii::t('shop', 'Cancel'), Url::to(['/shop/product']), [
            'class' => 'btn m-t-xs btn-danger btn-xs pull-right m-r-sm'
        ]); ?>

        <!--CONTENT-->
        <?= $this->render($viewName, $params); ?>
    </div>
</div>