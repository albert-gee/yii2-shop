<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $order \bl\cms\cart\models\Order
 * @var $address \bl\cms\cart\common\components\user\models\UserAddress
 */
use yii\helpers\Html;

?>
<?php if (!empty($order->deliveryMethod)): ?>
    <h3><?= Yii::t('cart', 'Delivery'); ?></h3>
    <p><?= Html::tag('strong', Yii::t('cart', 'Delivery method')) . ': ' .
        $order->deliveryMethod->translation->title; ?>
    </p>
    <p><i><?= $order->deliveryMethod->translation->description; ?></i></p>

    <?php if (!empty($order->delivery_post_office)) : ?>
        <p><?= Html::tag('strong', Yii::t('cart', 'Post office')) . ': ' . $order->delivery_post_office; ?></p>
    <?php else : ?>
        <p><?= (!empty($address->country)) ?
                Html::tag('strong', Yii::t('cart', 'Country')) . ': ' . $address->country : ''; ?></p>
        <p><?= (!empty($address->region)) ?
                Html::tag('strong', Yii::t('cart', 'Region')) . ': ' . $address->region : ''; ?></p>
        <p><?= (!empty($address->city)) ?
                Html::tag('strong', Yii::t('cart', 'City')) . ': ' . $address->city : ''; ?></p>
        <p><?= (!empty($address->house)) ?
                Html::tag('strong', Yii::t('cart', 'House')) . ': ' . $address->house : ''; ?></p>
        <p><?= (!empty($address->apartment)) ?
                Html::tag('strong', Yii::t('cart', 'Apartment')) . ': ' . $address->apartment : ''; ?></p>
        <p><?= (!empty($address->zipcode)) ?
                Html::tag('strong', Yii::t('cart', 'Zip')) . ': ' . $address->zipcode : ''; ?></p>
    <?php endif; ?>
<?php endif; ?>
