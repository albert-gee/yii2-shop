<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $model \xalberteinsteinx\shop\common\entities\Currency
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Editing currency'); ?>

<div class="ibox">

    <div class="ibox-title">
        <h5>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= Html::encode($this->title); ?>
        </h5>
    </div>

    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'value')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('shop', 'Create') : Yii::t('shop', 'Edit'),
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' . ' btn-xs']) ?>
                <?= Html::a(Yii::t('shop', 'Close'), Url::toRoute('/shop/currency'), ['class' => 'btn btn-danger btn-xs']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>


