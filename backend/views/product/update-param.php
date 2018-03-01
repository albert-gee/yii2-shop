<?php
use sointula\shop\common\entities\ParamTranslation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $paramTranslation ParamTranslation
 * @var $languageId integer
 */

?>

<?php $form = ActiveForm::begin([
    'action' => [
        'product/update-param',
        'id' => $paramTranslation->param_id,
        'languageId' => $languageId
    ],
    'method' => 'post',
    'options' => [
        'class' => 'param',
        'data-pjax' => true
    ]
]);
?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th class="col-md-5 text-center">
            <?= \Yii::t('shop', 'Title'); ?>
        </th>
        <th class="col-md-6 text-center">
            <?= \Yii::t('shop', 'Value'); ?>
        </th>
        <th class="col-md-1 text-center">
            <?= \Yii::t('shop', 'Control'); ?>
        </th>
    </tr>
    </thead>
    <tbody>

    <tr>
        <td>
            <?= $form->field($paramTranslation, 'name', [
                'inputOptions' => [
                    'class' => 'form-control col-md-5'
                ]
            ])->label(false)
            ?>
        </td>
        <td>
            <?= $form->field($paramTranslation, 'value', [
                'inputOptions' => [
                    'class' => 'form-control col-md-5'
                ]
            ])->label(false)
            ?>
        </td>
        <td>
            <?= Html::submitButton(\Yii::t('shop', 'Edit'), ['class' => 'btn btn-primary']) ?>
        </td>
    </tr>
    </tbody>
</table>

<?php $form->end(); ?>