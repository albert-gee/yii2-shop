<?php
use xalberteinsteinx\shop\widgets\LanguageSwitcher;
use marqu3s\summernote\Summernote;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $model xalberteinsteinx\shop\common\entities\DeliveryMethod
 * @var $modelTranslation xalberteinsteinx\shop\common\entities\DeliveryMethodTranslation
 * @var $languages \bl\multilang\entities\Language[]
 * @var $selectedLanguage \bl\multilang\entities\Language
 */

$this->title = ($modelTranslation->isNewRecord) ?
    Yii::t('cart', 'Creating new delivery method') :
    Yii::t('cart', 'Editing delivery method');
?>


<div class="box">

    <?php $form = ActiveForm::begin(); ?>
    <div class="box-title">
        <h5>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= Html::encode($this->title); ?>
        </h5>

        <!-- LANGUAGES -->
        <?= LanguageSwitcher::widget([
            'languages' => $languages,
            'selectedLanguage' => $selectedLanguage,
        ]); ?>

        <!--CANCEL BUTTON-->
        <?= Html::a(Yii::t('shop', 'Cancel'), Url::toRoute('delivery-method/index'),
            ['class' => 'pull-right btn btn-xs btn-danger m-r-xs m-t-xs']); ?>

        <!--SAVE BUTTON-->
        <?= Html::submitButton(Yii::t('shop', 'Save'),
            ['class' => 'pull-right btn btn-xs btn-primary m-r-xs m-t-xs']); ?>

    </div>

    <div class="box-content">
        <?= $form->field($modelTranslation, 'title')->textInput(['maxlength' => true]); ?>
        <?= $form->field($modelTranslation, 'description')->widget(Summernote::className()); ?>
        <?= $form->field($model, 'show_address_or_post_office')->dropDownList([
            $model::DO_NOT_SHOW_ADDRESS_OR_POST_OFFICE_FIELDS => \Yii::t('shop', 'Show nothing'),
            $model::SHOW_ADDRESS_FIELDS => \Yii::t('shop', 'Show address fields'),
            $model::SHOW_POST_OFFICE_FIELD => \Yii::t('shop', 'Show post office field')
        ]); ?>

        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'logo')->fileInput(); ?>
            </div>
            <div class="col-md-3">
                <?php if (!empty($model->image_name)) : ?>
                    <?= Html::img($model->smallLogo); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::a(Yii::t('shop', 'Cancel'), Url::toRoute('delivery-method/index'), ['class' => 'btn btn-xs btn-danger pull-right']); ?>
            <?= Html::submitButton(Yii::t('shop', 'Save'), ['class' => 'btn btn-xs btn-primary pull-right m-r-xs']); ?>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
