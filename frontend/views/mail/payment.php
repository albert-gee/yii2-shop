<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $order \bl\cms\cart\models\Order
 */
?>

<?php if (!empty($order->paymentMethod)) : ?>
    <h3><?= Yii::t('cart', 'Payment method'); ?></h3>
    <p>
        <b><?= $order->paymentMethod->translation->title ?? ''; ?></b>
    </p>
    <p>
        <i><?= $order->paymentMethod->translation->description ?? ''; ?></i>
    </p>
<?php endif; ?>
