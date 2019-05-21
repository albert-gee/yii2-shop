<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var albertgeeca\shop\common\components\user\models\Profile $model
 */

$this->title = \Yii::t('cart', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("$(\"[data-toggle='tooltip']\").tooltip();");
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<section class="personal-area">
    <h1 class="title">
        <?= $this->title ?>
    </h1>
    <div class="row">
        <div class="col-sm-5">
            <?php $form = ActiveForm::begin([
                'id' => 'profile-form',
                'enableClientValidation' => false
            ]); ?>
            <?= $form->field($model, 'name')
                ->label(\Yii::t('cart', 'Name')) ?>
            <?= $form->field($model, 'surname')
                ->label(\Yii::t('cart', 'Surname')) ?>
            <?= $form->field($model, 'patronymic')
                ->label(\Yii::t('cart', 'Patronymic')) ?>
            <?= $form->field($model, 'info')
                ->label(\Yii::t('cart', 'Info')) ?>
            <?= $form->field($model, 'phone')
                ->widget(MaskedInput::className(), [
                    'mask' => '+38-(999)-999-99-99'])
                ->label(\Yii::t('cart', 'Phone')); ?>
            <?= Html::submitButton(\Yii::t('cart', 'Save'), ['class' => 'btn btn-primary']); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>
