<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $searchModel sointula\shop\common\entities\SearchAttribute
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use bl\multilang\entities\Language;
use rmrevin\yii\fontawesome\FA;
use sointula\shop\widgets\ManageButtons;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">

    <div class="box-title">
        <h1><?= FA::i(FA::_TH_LIST) . ' ' . Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a(
                FA::i(FA::_USER_PLUS) . ' ' . \Yii::t('shop', 'Add'),
                    Url::toRoute(['save', 'languageId' => Language::getCurrent()->id]),
                    ['class' => 'btn btn-xs']) ?>
        </p>
    </div>

    <div class="box-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterRowOptions' => ['class' => 'm-b-sm m-t-sm'],
            'options' => [
                'class' => 'project-list'
            ],
            'tableOptions' => [
                'id' => 'my-grid',
                'class' => 'table'
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
                    'attribute' => \Yii::t('shop', 'Control'),

                    'value' => function ($model) {

                        return ManageButtons::widget([
                            'model' => $model,
                            'deleteUrl' => Url::toRoute(['remove', 'id' => $model->id])
                        ]);
                    },
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'col-md-2 text-center'],
                ],
            ],
        ]); ?>
    </div>
</div>