<?php
use albertgeeca\shop\common\components\user\models\RecoveryForm;
use yii\bootstrap\Alert;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var RecoveryForm $model
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

\frontend\assets\AnimateCssAsset::register($this);
?>

<div class="row">
    <div class="col-xs-12">
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                <?= Alert::widget([
                    'options' => ['class' => 'alert-dismissible alert-'.$type],
                    'body' => $message
                ]) ?>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>

<section class="login-form animated fadeInRight">
    <h1 class="title">
        <?= Yii::t('user', 'Recover your password') ?>
    </h1>
    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false
    ]) ?>
    <div class="form-group">
        <?= $form->field($model, 'email', [
            'inputOptions' => [
                'class' => '',
                'autofocus' => '',
                'tabindex' => 1
            ]
        ])->textInput(['type' => 'email']) ?>
    </div>
    <div class="form-group">
        <button type="submit" tabindex="4">
            <span class="btn-bg"></span>
            <span class="btn-label">
                       <?= Yii::t('user', 'Finish') ?>
                  </span>
        </button>
    </div>
    <?php $form->end() ?>
    <div class="m-t-20">
</section>