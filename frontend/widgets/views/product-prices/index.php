<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $renderView string
 * @var $params array
 *
 * Ex.: echo \xalberteinsteinx\shop\frontend\widgets\ProductPrices::widget([
 *  'product' => $product,
 *  'form' => $form,
 *  'defaultCombination' => $defaultCombination
 * ]);
 */
use xalberteinsteinx\shop\frontend\widgets\AdditionalProducts;
use yii\helpers\Html;

?>

<div class="product-prices-widget" data-not-available-text="<?= $params['notAvailableText']; ?>">

    <?php if ($params['enableCache']): ?>
        <?php if ($this->beginCache(
            $params['product']->id,
            (!empty($params['cacheDuration'])) ?
                ['duration' => $params['cacheDuration']] : ['dependency' => $params['dependency']])): ?>
            <?= $this->render($renderView, $params); ?>
            <?php $this->endCache(); ?>
        <?php endif; ?>

    <?php else: ?>

        <?= $this->render($renderView, $params); ?>
    <?php endif; ?>

    <?= $params['form']->field($params['cart'], 'productId', [
        'template' => '{input}',
        'options' => []
    ])
        ->hiddenInput(['value' => $params['product']->id, 'id' => 'productId'])
        ->label(false) ?>


    <?php if ($params['showCounter']): ?>
        <div class="form-group">
            <div class="quantity">
                <p class="count">
                    <?= Yii::t('shop', 'Count') ?>:
                </p>
                <?= $params['form']->field($params['cart'], 'count')
                    ->textInput(['type' => 'number', 'autocomplete' => 'off', 'value' => 1, 'min' => 1])
                    ->label(false) ?>
            </div>
        </div>
    <?php else: ?>
        <?= $params['form']->field($params['cart'], 'count')->hiddenInput(['value' => 1])->label(false); ?>
    <?php endif ?>

    <?php if($params['showAdditionalProducts']): ?>
        <?= AdditionalProducts::widget([
            'productId' => $params['product']->id,
            'form' => $params['form'],
            'model' => $params['cart'],
            'attribute' => 'additional_products'
        ]); ?>
    <?php endif; ?>

    <?php $icon = Html::tag('i', '', ['class' => 'si-shopping-cart']); ?>
    <?= Html::submitButton($icon . Yii::t('shop', 'To cart'), ['class' => 'btn btn-primary cart-btn', 'id' => 'add-to-cart-button']) ?>
</div>