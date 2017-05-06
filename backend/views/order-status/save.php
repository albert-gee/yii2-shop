<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $model xalberteinsteinx\shop\common\entities\OrderStatus
 * @var $modelTranslation xalberteinsteinx\shop\common\entities\OrderStatusTranslation
 * @var $selectedLanguage \bl\multilang\entities\Language
 * @var $languages \bl\multilang\entities\Language[]
 */

use bl\emailTemplates\models\entities\EmailTemplate;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use xalberteinsteinx\shop\widgets\LanguageSwitcher;
use marqu3s\summernote\Summernote;

$this->title = ($modelTranslation->isNewRecord) ?
    Yii::t('cart', 'Creating new order status') :
    Yii::t('cart', 'Editing order status');
?>
<div class="box">
    <?php $form = ActiveForm::begin(); ?>

    <div class="box-title">

        <h1>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= Html::encode($this->title); ?>
        </h1>

        <!-- LANGUAGES -->
        <?= LanguageSwitcher::widget([
            'languages' => $languages,
            'selectedLanguage' => $selectedLanguage,
        ]); ?>
        <!--CANCEL BUTTON-->
        <?= Html::a(Yii::t('cart', 'Close'), Url::toRoute('/cart/order-status'), ['class' => 'pull-right btn btn-xs btn-danger m-r-xs m-t-xs']); ?>
        <!--SAVE BUTTON-->
        <?= Html::submitButton(Yii::t('cart', 'Save'), ['class' => 'pull-right btn btn-xs btn-primary m-r-xs m-t-xs']); ?>

    </div>

    <div class="box-content">

        <?= $form->field($modelTranslation, 'title')->textInput(['maxlength' => true]); ?>
        <?= $form->field($model, 'color')->input('color'); ?>
        <?= $form->field($modelTranslation, 'description')->widget(Summernote::className())->label(\Yii::t('cart', 'Description')); ?>
        <?= $form->field($model, 'mail_id')->dropDownList(ArrayHelper::map(
            EmailTemplate::find()->all(), 'id', 'key'
        ), ['prompt' => \Yii::t('cart', 'Do not send email')]); ?>

        <?= Html::tag('em',
            Yii::t('cart', 'You can use the next variables in your mail:') .
            Html::ul([
                '{status} - ' . Yii::t('cart', 'Order status'),
                '{order_id} - ' . Yii::t('cart', 'Order unique id'),
                '{created_at} - ' . Yii::t('cart', 'Date and time of creating')
            ])
        );?>

        <div class="row">
            <?= Html::submitButton(Yii::t('cart', 'Save'), ['class' => 'btn btn-primary btn-xs m-r-xs pull-right']); ?>
            <?= Html::a(Yii::t('cart', 'Close'), Url::toRoute('/cart/order-status'), ['class' => 'm-r-xs btn btn-danger btn-xs pull-right']); ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>