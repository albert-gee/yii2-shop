<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this               yii\web\View
 * @var $model              \albertgeeca\shop\common\entities\Currency
 * @var $rates              \albertgeeca\shop\common\entities\Currency[]
 * @var $modifiedElement    integer|null
 */

use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = Yii::t('shop', 'Currency');
?>

<div class="box">

    <div class="box-title">
        <h1>
            <?= FA::i(FA::_MONEY) . ' ' . Html::encode($this->title); ?>
        </h1>
    </div>

    <div class="box-content col-md-8 block-center">

        <?php Pjax::begin([
            'id' => 'p-currency',
            'enablePushState' => false,
            'enableReplaceState' => false,
        ]); ?>

        <?php $form = ActiveForm::begin([
            'options' => [
                'data-pjax' => true,
            ]
        ]); ?>

        <table class="table">
            <tr>
                <th class="col-md-1">#</th>
                <th class="col-md-7"><?= Yii::t('shop', 'Rate'); ?></th>
                <th class="col-md-2 text-center"><?= Yii::t('shop', 'Date'); ?></th>
                <th class="col-md-2"></th>
            </tr>
            <tr>
                <th></th>
                <th>
                    <?= $form->field($model, 'value')->textInput()->label(false); ?>
                </th>
                <th class="text-center">
                    <?= Yii::$app->formatter->asDate(date('Y-m-d')); ?>
                </th>
                <th>
                    <?= Html::submitButton(
                        Html::tag('span', FA::i(FA::_USER_PLUS) . ' ' . \Yii::t('shop', 'Add')),
                        ['class' => 'btn btn-primary']) ?>
                </th>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>

        <table class="table">
            <?php foreach ($rates as $rate) : ?>
                <tr class="<?= ($modifiedElement == $rate->id) ? 'modifiedElement' : ''; ?>">
                    <td class="col-md-1">
                        <?= $rate->id; ?>
                    </td>
                    <td class="col-md-7">
                        <?php $valueForm = ActiveForm::begin([
                            'method' => 'post',
                            'action' => [
                                'update',
                                'id' => $rate->id,
                            ],
                            'options' => [
                                'data-pjax' => true
                            ]
                        ]); ?>
                        <?= $valueForm->field($rate, 'value')->textInput() ?>

                        <?= Html::submitButton(Yii::t('shop', 'Edit'),
                            ['class' => 'btn btn-primary btn-in-input']) ?>
                        <?php $valueForm->end(); ?>

                    </td>
                    <td class="col-md-2 text-center">
                        <?= Yii::$app->formatter->asDate($rate->date); ?>
                    </td>
                    <td class="col-md-2">

                        <?= Html::a(FA::i(FA::_REMOVE),
                            Url::to(['remove', 'id' => $rate->id]),
                            ['class' => 'btn btn-danger btn-xs']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php Pjax::end(); ?>

    </div>
</div>


