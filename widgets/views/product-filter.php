<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $category Category
 * @var $filters Filter
 * @var $searchModel ProductSearch
 */

use sointula\shop\common\entities\Category;
use sointula\shop\common\entities\Filter;
use sointula\shop\frontend\components\ProductSearch;
use sointula\shop\widgets\assets\ProductFilterAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

ProductFilterAsset::register($this);
?>

<?php $form = ActiveForm::begin([
    'action' => [
        '/shop/category/show',
        'id' => $category->id
    ],
    'method' => 'get',
    'options' => ['data-pjax' => true]
]);
?>

<?php foreach ($filters as $filter) : ?>
    <?php
    $newObject = Filter::getCategoryFilterValues($filter, $category->id);
    $inputId = $filter->inputType->id;
    $inputType = $filter->inputType->type;
    ?>

    <?php if ($inputId == 1 || $inputId == 2 || $inputId == 3) : ?>
        <?= $form->field($searchModel, $filter->type->column)
            ->$inputType(ArrayHelper::map($newObject, 'id', $filter->type->displaying_column),
                ['prompt' => '', 'name' => $filter->type->column])->label(\Yii::t('shop', $filter->type->title)); ?>

    <?php elseif ($inputId == 4) : ?>
        <?= $form->field($searchModel, $filter->type->column)
            ->textInput(['options' => ['prompt' => '', 'name' => $filter->type->column]])->label(\Yii::t('shop', $filter->type->title)); ?>

    <?php endif; ?>

<?php endforeach; ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('shop', 'Filter'), ['class' => 'pjax btn btn-primary filter-button', 'id' => 'qwe']); ?>
</div>

<?php ActiveForm::end(); ?>