<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \albertgeeca\shop\common\components\user\models\User $model
 * @var \albertgeeca\shop\common\components\user\models\Profile $profile
 *
 */

?>

<div class="col-md-12">
    <h1 class="title"><?= Yii::t('frontend', 'Sign up') ?></h1>
</div>

<div class="col-md-12">
    <section class="login-form ">
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => true,
                'enableAjaxValidation' => true
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
                <?= $form->field($model, 'password', [
                    'inputOptions' => [
                        'class' => '',
                        'autofocus' => '',
                        'tabindex' => 3
                    ]
                ])
                    ->passwordInput()
                    ->label(Yii::t('cart', 'Password')) ?>
            </div>

            <br>
            <!--surname-->
            <div class="form-group">
                <?= $form->field($profile, 'surname', [
                    'inputOptions' => [
                        'class' => '',
                        'autofocus' => '',
                        'tabindex' => 5
                    ]
                ]) ?>
            </div>
            <!--NAME-->
            <div class="form-group">
                <?= $form->field($profile, 'name', [
                    'inputOptions' => [
                        'class' => '',
                        'autofocus' => '',
                        'tabindex' => 4
                    ]
                ]) ?>
            </div>
            <!--patronymic-->
            <div class="form-group">
                <?= $form->field($profile, 'patronymic', [
                    'inputOptions' => [
                        'class' => '',
                        'autofocus' => '',
                        'tabindex' => 4
                    ]
                ]) ?>
            </div>
            <!--phone-->
            <div class="form-group">
                <?= $form->field($profile, 'phone', [
                    'inputOptions' => [
                        'class' => '',
                        'autofocus' => '',
                        'tabindex' => 6
                    ]
                ]) ?>
            </div>
            <div class="form-group">
                <button type="submit" tabindex="4">
                    <span class="btn-bg"></span>
                    <span class="btn-label">
                           <?= Yii::t('user', 'Sign up') ?>
                      </span>
                </button>
            </div>
            <?php $form->end() ?>
            <div class="m-t-20">
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
                </p>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
                </p>
            </div>
        </div>
    </section>
</div>
