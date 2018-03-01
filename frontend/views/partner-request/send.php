<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \sointula\shop\common\entities\PartnerRequest $partner
 * @var \sointula\shop\common\components\user\models\Profile $profile
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = (!empty($this->context->staticPage->translation->title)) ?
    $this->context->staticPage->translation->title :
    Yii::t('frontend', 'Partners request');
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="other">
    <h1 class="text-center m-b-20">
        <?= $this->title ?>
    </h1>
    <div class="row">
        <?php if (!empty($this->context->staticPage->translation->text)) : ?>
            <div class="col-md-12">
                <?= $this->context->staticPage->translation->text; ?>
            </div>
        <?php endif; ?>
        <div class="col-md-6 col-md-offset-3">
            <?php if (Yii::$app->user->isGuest): ?>
                <p>
                    <?= Yii::t(
                        'frontend',
                        'For sending the request for partners you should to {signin} or {register} if you don\'t have a account', [
                        'signin' => Html::a(Yii::t('frontend', 'SignIn'), Url::to('@signin')),
                        'register' => Html::a(Yii::t('frontend', 'Register'), Url::to('@register'))
                    ]) ?>
                </p>
            <?php elseif (!Yii::$app->user->isGuest && Yii::$app->user->identity->getPartnerStatus()) : ?>
                <h3 class="text-center">
                    <?= Yii::t('shop', 'You have already sent a request.') ?>
                </h3>
            <?php else : ?>
                <h2 class="text-center m-t-20">
                    <?= Yii::t('frontend', 'Send a request') ?>
                </h2>
                <?php $form = ActiveForm::begin([
                    'action' => ['partner-request/send'],
                    'enableClientValidation' => false
                ]) ?>
                <!-- Request form -->
                <?php $partner->contact_person = $profile->name . ' ' . $profile->surname; ?>
                <?= $form->field($partner, 'contact_person')
                    ->textInput()->label(\Yii::t('shop', 'Contact person')); ?>
                <?= $form->field($partner, 'company_name', ['inputOptions' => ['class' => '']])
                ->label(Yii::t('shop', 'Company name')) ?>
                <?= $form->field($partner, 'website', ['inputOptions' => ['class' => '']])
                ->label(Yii::t('shop', 'Website')) ?>
                <?= $form->field($partner, 'message', ['inputOptions' => ['class' => '']])
                ->textarea(['rows' => 7])
                ->label(Yii::t('shop', 'Message')) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('shop', 'Send'), ['class' => 'button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>
