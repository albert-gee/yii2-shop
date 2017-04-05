<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $model
 */

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = $this->title;
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
            <?= $form->field($model, 'email', ['inputOptions' => ['class' => '']])?>
            <?= $form->field($model, 'new_password', ['inputOptions' => ['class' => '']])
                    ->passwordInput() ?>
            <hr>
            <?= $form->field($model, 'current_password', ['inputOptions' => ['class' => '']])
                    ->passwordInput() ?>
            <div class="form-group">
                <button type="submit">
                    <span class="btn-bg"></span>
                    <span class="btn-label">
                            <?= Yii::t('user', 'Save') ?>
                        </span>
                </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>
