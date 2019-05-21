<?php
use marqu3s\summernote\Summernote;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $model albertgeeca\shop\common\entities\ProductAvailability
 * @var $modelTranslation albertgeeca\shop\common\entities\ProductAvailabilityTranslation
 * @var $languages \bl\multilang\entities\Language[]
 * @var $selectedLanguage \bl\multilang\entities\Language
 */

$this->title = ($model->isNewRecord) ? Yii::t('shop', 'Create availability status') :
    Yii::t('shop', 'Change availability status');

$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    [
        'url' => Url::to(['/shop/product-availability/index']),
        'label' => Yii::t('shop', 'Availability statuses')
    ],
    $this->title
];
?>


<div class="box">

    <div class="box-title">
        <h1>
            <?= FA::i(FA::_TASKS) . ' ' . Html::encode($this->title); ?>
        </h1>

        <!--LANGUAGES-->
        <?= \albertgeeca\shop\widgets\LanguageSwitcher::widget([
            'selectedLanguage' => $selectedLanguage,
        ]); ?>

    </div>

    <div class="panel-body">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($modelTranslation, 'title')->textInput(['maxlength' => true]); ?>
        <?= $form->field($modelTranslation, 'description')->widget(Summernote::className()); ?>

        <div class="form-group">
            <?= Html::a(Yii::t('shop', 'Close'), Url::toRoute('product-availability/index'), ['class' => 'btn btn-xs btn-danger pull-right']); ?>
            <?= Html::submitButton(Yii::t('shop', 'Save'), ['class' => 'btn btn-xs btn-primary pull-right m-r-xs']); ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>

</div>
