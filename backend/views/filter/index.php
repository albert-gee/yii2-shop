<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $searchModel sointula\shop\common\entities\SearchFilterType
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('shop', 'Filters');
?>
<div class="box-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('shop', 'Create new filter'), ['save'], ['class' => 'btn btn-primary btn-xs pull-right']) ?>
    </p>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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

            /*TITLE*/
            [
                'headerOptions' => ['class' => 'text-center col-md-4'],
                'attribute' => 'title',
                'value' => function ($model) {
                    return Html::a(
                        $model->title,
                        Url::toRoute(['save', 'id' => $model->id])
                    );
                },
                'label' => Yii::t('shop', 'Title'),
                'format' => 'html',
                'contentOptions' => ['class' => 'project-title col-md-3'],
            ],

            [
                'headerOptions' => ['class' => 'text-center col-md-4'],
                'attribute' => 'class_name',
                'value' => 'class_name',
            ],
            [
                'headerOptions' => ['class' => 'text-center col-md-2'],
                'attribute' => 'column',
                'value' => 'column',
            ],
            [
                'headerOptions' => ['class' => 'text-center col-md-2'],
                'attribute' => 'displaying_column',
                'value' => 'displaying_column',
            ],


            /*ACTIONS*/
            [
                'headerOptions' => ['class' => 'text-center col-md-1'],
                'attribute' => \Yii::t('shop', 'Control'),

                'value' => function ($model) {

                    $buttons =
                        Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['save', 'id' => $model->id]),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-primary btn-xs pjax']) .
                        Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::toRoute(['delete', 'id' => $model->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger pull-right btn-xs pjax']);

                    return $buttons;
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'col-md-2 text-center'],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>