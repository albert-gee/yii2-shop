<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */

$this->title = Yii::t('cart', 'Your order is accepted.');
$this->params['breadcrumbs'][] = $this->title;

bl\cms\cart\frontend\assets\CartAsset::register($this);
?>

<div class="cart">

    <article>
        <h1><?= $this->title; ?></h1>
        <p><?= Yii::t('cart', 'Our manager will contact you as soon as possible'); ?></p>
    </article>
</div>