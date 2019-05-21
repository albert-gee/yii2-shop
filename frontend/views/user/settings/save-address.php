<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 *  @var \albertgeeca\shop\common\components\user\models\UserAddress $address
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($address, 'country') ?>
        <?= $form->field($address, 'region') ?>
        <?= $form->field($address, 'city') ?>
        <?= $form->field($address, 'street') ?>
        <?= $form->field($address, 'house') ?>
        <?= $form->field($address, 'apartment') ?>
        <?= $form->field($address, 'zipcode') ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
