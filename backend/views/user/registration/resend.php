<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

\frontend\assets\AnimateCssAsset::register($this);
?>

<section class="login-form animated fadeInRight">
    <h1 class="title">
        <?= Yii::t('user', 'Request new confirmation message') ?>
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
                       <?= Yii::t('user', 'Continue') ?>
                  </span>
        </button>
    </div>
    <?php $form->end() ?>
    <div class="m-t-20">
</section>
