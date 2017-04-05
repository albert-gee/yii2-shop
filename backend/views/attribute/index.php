<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $searchModel xalberteinsteinx\shop\common\entities\SearchAttribute
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use bl\multilang\entities\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('shop', 'Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">

    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a(Yii::t('shop', 'Create attribute'), Url::toRoute(['save', 'languageId' => Language::getCurrent()->id]), ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="panel-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'filterRowOptions' => ['class' => 'm-b-sm m-t-sm'],
            'options' => [
                'class' => 'project-list'
            ],
            'tableOptions' => [
                'id' => 'my-grid',
                'class' => 'table table-hover'
            ],
            'summary' => "",

            'columns' => [
                [
                    'attribute' => 'Id',
                    'value' => 'id',
                    'headerOptions' => ['class' => 'text-center'],
                    'contentOptions' => ['class' => 'text-center']
                ],
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => 'title',
                    'value' => 'translation.title',
                    'label' => Yii::t('shop', 'Title'),
                    'format' => 'text',
                    'contentOptions' => ['class' => 'text-center project-title col-md-2'],
                ],
                [
                    'attribute' => 'type',
                    'value' => function($model) {
                        return \Yii::t('shop', $model->type->title);
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'value' => 'created_at',
                ],
                [
                    'attribute' => 'updated_at',
                    'value' => 'updated_at',
                ],

                /*ACTIONS*/
                [
                    'headerOptions' => ['class' => 'text-center col-md-2'],
                    'attribute' => \Yii::t('shop', 'Manage'),

                    'value' => function ($model) {

                        global $product;
                        $product = $model;

                        $languages = Language::findAll(['active' => true]);
                        $list =
                            Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::toRoute(['remove', 'id' => $model->id]),
                                ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger pull-right pjax']) .

                            Html::tag('div',
                                Html::a(
                                    'Edit',
                                    Url::toRoute(['save', 'attrId' => $model->id, "languageId" => Language::getCurrent()->id]),
                                    [
                                        'class' => 'col-md-8 btn btn-default ',
                                    ]) .
                                Html::a(
                                    '<span class="caret"></span>',
                                    Url::toRoute(['save', 'attrId' => $model->id, "languageId" => Language::getCurrent()->id]),
                                    [
                                        'class' => 'block col-md-4 btn btn-default dropdown-toggle',
                                        'type' => 'button', 'id' => 'dropdownMenu1',
                                        'data-toggle' => 'dropdown', 'aria-haspopup' => 'true',
                                        'aria-expanded' => 'true'
                                    ]) .
                                Html::ul(
                                    ArrayHelper::map($languages, 'id', 'name'),
                                    [
                                        'item' => function ($item, $index) {
                                            return Html::tag('li',
                                                Html::a($item, Url::toRoute(['save', 'attrId' => $GLOBALS['product']->id, "languageId" => $index]), []),
                                                []
                                            );
                                        },
                                        'class' => 'dropdown-menu', 'aria-labelledby' => 'dropdownMenu1']),

                                ['class' => 'btn-group pull-left']
                            );

                        return $list;
                    },
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'col-md-2 text-center'],
                ],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>