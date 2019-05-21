<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $searchModel albertgeeca\shop\common\entities\SearchViewedProduct
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use bl\multilang\entities\Language;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Viewed products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="viewed-product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'project-list'
        ],
        'summary' => "",

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            /*SKU*/
            [
                'headerOptions' => ['class' => 'text-center col-md-1'],
                'attribute' => 'product.sku',
                'value' => function ($model) {
                    if (!empty($model->product->sku)) {
                        return $model->product->sku;
                    }
                    return '';
                },
                'label' => Yii::t('shop', 'SKU'),
                'format' => 'html',
                'contentOptions' => ['class' => 'project-title'],
            ],

            /*TITLE AND INFO*/
            [
                'headerOptions' => ['class' => 'text-center col-md-6'],
                'attribute' => 'product.translation.title',
                'value' => function ($model) {
                    if (!empty($model->product->translation->title)) {
                        $content = (!empty($model->product->translation->title)) ?
                            Html::tag('h3', Html::a(
                                $model->product->translation->title,
                                Url::toRoute(['/shop/product/show',
                                    'id' => $model->product->id, 'languageId' => Language::getCurrent()->id]),
                                ['class' => "text-success"])) : '';

                        $content .= (!empty($model->product->category->translation->title)) ?
                            Html::tag('p',
                                Html::tag('small', Yii::t('shop', 'Category') . ': ' .
                                    Html::a(
                                        $model->product->category->translation->title,
                                        Url::toRoute(['/shop/category/show',
                                            'id' => $model->product->category_id, 'languageId' => Language::getCurrent()->id]),
                                        ['class' => 'text-info']
                                    )),
                                ['class' => 'text-info'])
                            : '';

                        $content .= (!empty($model->product->translation->description)) ?
                            $model->product->translation->description : '';

                        $content .= Html::tag('footer',
                            '<small>' . Yii::t('shop', 'Last view') . ' ' . $model->update_time . '</small>');
                        return $content;
                    }
                    return '';
                },
                'label' => Yii::t('shop', 'Title'),
                'format' => 'html',
            ],

            /*IMAGE*/
            [
                'headerOptions' => ['class' => 'text-center col-md-2'],
                'attribute' => 'image',
                'value' => function ($model) {

                    $imageUrl = (!empty($model->product->image)) ? $model->product->image->small :
                        '/images/default.jpg';

                    return Html::a(
                        Html::img(Url::toRoute([$imageUrl])),
                        Url::toRoute(['/shop/product/show',
                            'id' => $model->product->id, 'languageId' => Language::getCurrent()->id]));

                },
                'label' => Yii::t('shop', 'Image'),
                'format' => 'raw',
                'contentOptions' => ['class' => 'col-md-4 text-center'],
            ],

            /*BUTTONS*/
            [
                'headerOptions' => ['class' => 'text-center col-md-3'],
                'value' => function ($model) {
                    $deleteButton = Html::a(
                        Yii::t('shop', 'Remove from viewed'),
                        Url::to(['/shop/viewed-product/delete', 'id' => $model->product_id]),
                        ['class' => 'btn btn-warning col-md-12 m-t-xs']);
                    $goToButton = Html::a(
                        Yii::t('shop', 'Go to product'),
                        Url::to(['/shop/product/show', 'id' => $model->product_id]),
                        ['class' => 'btn btn-primary col-md-12']);
                    return $goToButton . $deleteButton;
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
            ]
        ],
    ]); ?>

    <?= Html::a(
        Yii::t('shop', 'Clear list'),
        Url::to(['/shop/viewed-product/clear-list']),
        ['class' => 'btn btn-danger pull-right m-t-xs']);?>
</div>