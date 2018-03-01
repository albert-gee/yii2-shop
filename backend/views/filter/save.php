<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model sointula\shop\common\entities\FilterType */
/* @var $this yii\web\View */
/* @var $model sointula\shop\common\entities\FilterType */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('shop', 'Create new filter type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('shop', 'Filter Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="filter-type-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'class_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'column')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'displaying_column')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::a(Yii::t('shop', 'Cancel'), Url::to(['/shop/filter']), ['class' => 'btn btn-danger btn-xs pull-right']) ?>
            <?= Html::submitButton(Yii::t('shop', 'Save'), ['class' => 'btn btn-primary btn-xs pull-right m-r-xs']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>