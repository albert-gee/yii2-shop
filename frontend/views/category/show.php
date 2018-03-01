<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $searchModel sointula\shop\frontend\components\ProductSearch
 * @var $dataProvider sointula\shop\frontend\components\ProductSearch
 *
 * @var $category Category
 * @var $menuItems Category
 * @var $filters Filter
 * @var $products Product
 * @var $cart \sointula\shop\frontend\components\forms\CartForm
 *
 */

use sointula\shop\common\entities\Category;
use sointula\shop\common\entities\Filter;
use sointula\shop\common\entities\Product;
use sointula\shop\frontend\assets\CategoryAsset;
use yii\widgets\ListView;
use yii\widgets\Pjax;


if (empty($this->title) && !empty($category->translation->title)) {
    $this->title = $category->translation->title;
}

if(!empty($category->translation->title)) {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('shop', 'Products catalog'),
        'url' => ['/shop/category/show'],
        'itemprop' => 'url'
    ];
    $this->params['breadcrumbs'][] = $category->translation->title;
} else {
    $this->params['breadcrumbs'][] = Yii::t('shop', 'Products catalog');
}

CategoryAsset::register($this);
?>

<?php Pjax::begin([
    'linkSelector' => '.pjax'
]); ?>

<!-- CATEGORIES -->
<div class="col-sm-4 col-md-3">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span><?= Yii::t('shop', 'Categories'); ?>:</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?= \sointula\shop\widgets\TreeWidget::widget([
                        'className' => Category::className(),
                        'currentCategoryId' => (!empty($category->id)) ? $category->id : null
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRODUCTS -->
<div class="col-sm-8 col-md-9">
    <div class="panel">
        <div class="panel-heading">
            <?php if (!empty($category)) : ?>
                <?php $categoryCover = $category->getImage('shop-category/cover', 'big'); ?>
                <div class="row">
                    <?php if (!empty($categoryCover)): ?>
                        <div class="thumbnail" style="background-image: url(<?= $categoryCover ?>); background-position: center; height: 200px;"></div>
                    <?php endif ?>
                    <!--TITLE-->
                    <h1 class="text-center"><?= $category->translation->title; ?></h1>
                </div>
            <?php endif; ?>

            <?php if (!empty($dataProvider->count)): ?>
                <div class="row">
                    <?= \sointula\shop\widgets\ProductSort::widget(); ?>
                </div>
            <?php endif ?>
        </div>

        <div class="panel-body">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => [
                    'class' => 'row'
                ],

                'itemView' => '_product',
                'itemOptions' => [
                    'tag' => 'div',
                    'class' => 'col-xs-6 col-sm-6 col-md-4',
                ],

                'layout' => "<div class='row'>{items}</div><div>{pager}<span class='pull-right'>{summary}</span></div>",
                'summary' => Yii::t('shop', 'Showing {begin} to {end} of {totalCount} ({pageCount} pages)'),
                'summaryOptions' => [
                    'tag' => 'div',
                    'class' => 'pull-right'
                ],

                'emptyText' => \yii\helpers\Html::tag('p', Yii::t('shop', 'This category does not have any products') . '.', [
                    'class' => 'text-center'
                ]),

            ]); ?>
        </div>
    </div>
    <div class="panel">
        <!--CATEGORY TITLE-->
        <?php if (!empty($category->translation->title)): ?>
            <h2><?= $category->translation->title ?></h2>
        <?php elseif (!empty($this->context->staticPage->translation->title)) : ?>
            <h2><?= $this->context->staticPage->translation->title; ?></h2>
        <?php endif; ?>

        <!--CATEGORY DESCRIPTION-->
        <?php if (!empty($category->translation->description)): ?>
            <p><?= $category->translation->description ?></p>
        <?php elseif (!empty($this->context->staticPage->translation->text)) : ?>
            <p><?= $this->context->staticPage->translation->text; ?></p>
        <?php endif; ?>
    </div>
</div>

<!--FILTERING-->
<?php if (!empty($category) && !empty($filters)) : ?>
    <div class="col-md-2">
        <p class="h3"><?= \Yii::t('shop', 'Filtering') ?></p>
        <?= \sointula\shop\widgets\ProductFilter::widget([
            'category' => $category,
            'filters' => $filters,
//            'searchModel' => $searchModel
        ]);
        ?>
    </div>
<?php endif; ?>
<?php Pjax::end(); ?>
