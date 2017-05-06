<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $model \xalberteinsteinx\shop\common\entities\Currency
 * @var $rates \xalberteinsteinx\shop\common\entities\Currency[]
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Currency');
?>

<div class="box">

    <div class="box-title">
        <div class="box-tools">
            <h5>
                <i class="glyphicon glyphicon-list">
                </i>
                <?= Html::encode($this->title); ?>
            </h5>
        </div>

        <div class="box-content">
            <?php $form = ActiveForm::begin(); ?>

            <table class="table table-hover">
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
                        <?= Html::submitButton(Yii::t('shop', 'Add'), ['class' => 'btn btn-primary text-center']) ?>
                    </th>
                </tr>

                <?php foreach ($rates as $rate) : ?>
                    <tr>
                        <td>
                            <?= $rate->id; ?>
                        </td>
                        <td>
                            <?= $rate->value; ?>
                        </td>
                        <td class="text-center">
                            <?= Yii::$app->formatter->asDate($rate->date); ?>
                        </td>
                        <td>
                            <?= Html::a('',
                                Url::to(['update', 'id' => $rate->id]),
                                ['class' => 'glyphicon glyphicon-edit btn btn-success']); ?>

                            <?= Html::a('',
                                Url::to(['remove', 'id' => $rate->id]),
                                ['class' => 'glyphicon glyphicon-remove btn btn-danger']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


