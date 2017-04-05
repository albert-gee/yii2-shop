<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $userGroupTranslation \xalberteinsteinx\shop\common\components\user\models\UserGroupTranslation
 * @var $languageId integer
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = \Yii::t('shop', 'Change user group');
?>

<div class="ibox">
    <div class="ibox-title">
        <div class="row">
            <h1 class="col-md-9">
                <?= $this->title; ?>
            </h1>
            <div class="col-md-3 pull-right">
                <?= \xalberteinsteinx\shop\widgets\LanguageSwitcher::widget([
                    'selectedLanguage' => \bl\multilang\entities\Language::findOne($languageId)
                ]); ?>
            </div>
        </div>
    </div>
    <div class="ibox-content">

        <?php $form = ActiveForm::begin([
            'layout' => 'horizontal'
        ]); ?>

        <?= $form->field($userGroupTranslation, 'title') ?>
        <?= $form->field($userGroupTranslation, 'description') ?>


        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-3">
                <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
                <?= Html::a(\Yii::t('shop', 'Cancel'), Url::to('show-user-groups'), [
                    'class' => 'btn btn-danger btn-block'
                ]); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>