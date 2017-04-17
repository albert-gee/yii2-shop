<?php
use bl\cms\cart\frontend\assets\CartAsset;
use yii\helpers\Html;
use yii\helpers\Url;

use bl\cms\shop\widgets\LastViewedProducts;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var \yii\web\View $this
 */

$this->title = $this->context->staticPage->translation->title ?? Yii::t('cart', 'Cart');
$this->params['breadcrumbs'][] = $this->title;

CartAsset::register($this);
?>

<div class="cart">
    <article>
        <h1 class="title">
            <?= $this->title ?>
        </h1>
        <p class="h3">
            <?= Yii::t('cart', 'Your cart is empty.') ?>
        </p>
        <?= Html::a(Yii::t('cart', 'Go to catalog'), Url::toRoute('/shop'), [
            'class' => 'button go-to-shop'
        ]) ?>
        <?= LastViewedProducts::widget(['num' => 4]) ?>
    </article>
</div>
