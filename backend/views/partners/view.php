<?php

use albertgeeca\shop\common\entities\PartnerRequest;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model albertgeeca\shop\common\entities\PartnerRequest */

$this->title = $model->company_name;
?>
<div class="partner-request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--MODERATION-->
    <?php if (Yii::$app->user->can('moderatePartnerRequest') && $model->moderation_status == PartnerRequest::STATUS_ON_MODERATION) : ?>
    <div class="panel-body">
        <h2><?= \Yii::t('shop', 'Moderation');?></h2>
        <p><?= \Yii::t('shop', 'This company status is "on moderation". You may accept or decline it.'); ?></p>
        <?= Html::a(\Yii::t('shop', 'Accept'), Url::toRoute(['change-partner-status', 'id' => $model->id, 'status' => PartnerRequest::STATUS_SUCCESS]), ['class' => 'btn btn-primary btn-xs']); ?>
        <?= Html::a(\Yii::t('shop', 'Decline'), Url::toRoute(['change-partner-status', 'id' => $model->id, 'status' => PartnerRequest::STATUS_DECLINED]), ['class' => 'btn btn-danger btn-xs']); ?>
        <?php endif; ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => Yii::t('shop', 'User'),
                'value' => $model->sender->email
            ],
            [
                'label' => 'Owner',
                'value' => $model->company_name,
            ],
            [
                'label' => Yii::t('shop', 'Website'),
                'value' => $model->website,
            ],
            [
                'label' => Yii::t('shop', 'Message'),
                'value' => $model->message,
            ],
            [
                'label' => Yii::t('shop', 'Created'),
                'value' => $model->created_at,
            ],
            [
                'attribute' => 'moderated_at',
                'label' => Yii::t('shop', 'Moderation status'),
                'format' => 'raw',
                'value' => call_user_func(function($data) {
                    switch ($data->moderation_status) {
                        case PartnerRequest::STATUS_DECLINED :
                            return Html::tag('p', \Yii::t('shop', 'Declined'), ['class' => 'btn btn-danger']);
                        case PartnerRequest::STATUS_SUCCESS :
                            return Html::tag('p', \Yii::t('shop', 'Accept'), ['class' => 'btn btn-primary']);
                        case PartnerRequest::STATUS_ON_MODERATION :
                            return Html::tag('p', \Yii::t('shop', 'On moderation'), ['class' => 'btn btn-warning']);
                            break;
                    }
                    return 'Unknown';
                }, $model)
            ],
            [
                'label' => Yii::t('shop', 'Moderated by'),
                'value' => $model->moderated_by,
            ],
            [
                'label' => Yii::t('shop', 'Moderated at'),
                'value' => $model->moderated_at,
            ],
        ]]);
    ?>

</div>
