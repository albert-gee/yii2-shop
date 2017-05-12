<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $modifiedElementId  integer
 * @var $languageId         integer
 * @var $params             \xalberteinsteinx\shop\common\entities\Param[]
 * @var $param_translation  ParamTranslation
 * @var $productId          integer
 */

use rmrevin\yii\fontawesome\FA;
use xalberteinsteinx\shop\common\entities\ParamTranslation;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-md-1 text-center">
                <?= \Yii::t('shop', 'Position'); ?>
            </th>
            <th class="col-md-5 text-center">
                <?= \Yii::t('shop', 'Title'); ?>
            </th>
            <th class="col-md-5 text-center">
                <?= \Yii::t('shop', 'Value'); ?>
            </th>
            <th class="col-md-1 text-center">
                <?= \Yii::t('shop', 'Control'); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($params)) : ?>
            <?php foreach ($params as $param) : ?>
                <tr class="text-center<?= ($modifiedElementId == $param->id) ? ' modifiedElement' : ''; ?>">
                    <td>
                        <?= Html::a(
                            '',
                            Url::toRoute(['up', 'id' => $param->id, 'languageId' => $languageId]),
                            [
                                'class' => 'fa fa-chevron-up'
                            ]
                        ); ?>
                        <?= $param->position; ?>
                        <?= $buttonDown = Html::a(
                            '',
                            Url::toRoute(['down', 'id' => $param->id, 'languageId' => $languageId]),
                            [
                                'class' => 'fa fa-chevron-down'
                            ]
                        ); ?>
                    </td>
                    <td>
                        <?php foreach ($param->translations as $translation) : ?>
                            <?php if ($translation->language_id == $languageId) : ?>

                                <?= $translation->name ?? '' ?>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>

                    <td>
                        <?php foreach ($param->translations as $translation) : ?>
                            <?php if ($translation->language_id == $languageId) : ?>

                                <?= $translation->value ?? '' ?>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>

                    <td>
                        <a href="<?= Url::to(['update-param', 'id' => $param->id, 'languageId' => $languageId]); ?>" class="btn">
                            <span><?= FA::i(FA::_EDIT); ?></span>
                        </a>

                        <a href="<?= Url::to(['delete-param', 'id' => $param->id, 'languageId' => $languageId]); ?>" class="btn btn-danger">
                            <span><?= FA::i(FA::_REMOVE); ?></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        </tbody>
    </table>

<?php $form = ActiveForm::begin([
    'action' => [
        'product-param/add-param',
        'id' => $productId,
        'languageId' => $languageId
    ],
    'method' => 'post',
    'options' => [
        'id' => 'product-param-' . $productId,
        'data-pjax' => true,
    ]
]);
?>
    <table class="table">
        <tbody>
        <tr>
            <td class="col-md-1">
            </td>
            <td class="col-md-5">
                <?= $form->field($param_translation, 'name', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->label(false)
                ?>
            </td>
            <td class="col-md-5">
                <?= $form->field($param_translation, 'value', [
                    'inputOptions' => [
                        'class' => 'form-control'
                    ]
                ])->label(false)
                ?>
            </td>
            <td class="col-md-1 text-center">
                <?= Html::submitButton(
                    Html::tag('span', FA::i(FA::_USER_PLUS) . ' ' . \Yii::t('shop', 'Add')),
                    ['class' => 'btn btn-xs btn-primary pjax']);
                ?>
            </td>
        </tr>
        </tbody>
    </table>

<?php $form->end(); ?>