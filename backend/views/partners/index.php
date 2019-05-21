<?php

use albertgeeca\shop\common\entities\PartnerRequest;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel albertgeeca\shop\common\entities\SearchPartnerRequest */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('shop', 'Partner Requests');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            /*Sender*/
            [
                'headerOptions' => ['class' => 'text-center'],
                'value' => 'sender.email',
                'label' => Yii::t('shop', 'User'),
                'format' => 'text',
                'contentOptions' => ['class' => 'project-title'],
            ],
            [
                'headerOptions' => ['class' => 'text-center'],
                'value' => 'company_name',
                'label' => Yii::t('shop', 'Company name'),
                'format' => 'text',
                'contentOptions' => ['class' => 'project-title'],
            ],
            [
                'headerOptions' => ['class' => 'text-center'],
                'value' => 'website',
                'label' => Yii::t('shop', 'Website'),
                'format' => 'text',
                'contentOptions' => ['class' => 'project-title'],
            ],
            [
                'headerOptions' => ['class' => 'text-center'],
                'value' => 'created_at',
                'label' => Yii::t('shop', 'Created'),
                'format' => 'text',
                'contentOptions' => ['class' => 'project-title'],
            ],
            [
                'headerOptions' => ['class' => 'text-center'],
                'label' => Yii::t('shop', 'Status'),
                'format' => 'raw',
                'value' => function($model) {
                    switch ($model->moderation_status) {
                        case PartnerRequest::STATUS_DECLINED :
                            return Html::tag('p', \Yii::t('shop', 'Declined'), ['class' => 'btn btn-danger']);
                        case PartnerRequest::STATUS_SUCCESS :
                            return Html::tag('p', \Yii::t('shop', 'Accept'), ['class' => 'btn btn-primary']);
                        case PartnerRequest::STATUS_ON_MODERATION :
                            return Html::tag('p', \Yii::t('shop', 'On moderation'), ['class' => 'btn btn-warning']);
                            break;
                    }
                    return 'Unknown';
                }
            ],
            [
                'label' => 'Ссылка',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a(
                        'Перейти',
                        Url::toRoute(['view', 'id' => $data->id]),
                        [
                            'class' => 'btn btn-primary btn-xs'
                        ]
                    );
                }
            ],

            /*Disable action column*/
            [
                'class' => 'yii\grid\ActionColumn',
                'visible' => false
            ],
        ],
    ]); ?>
</div>
