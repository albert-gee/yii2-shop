<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $order \bl\cms\cart\models\Order
 */
use yii\bootstrap\Html;
use yii\helpers\Url;

\bl\cms\cart\frontend\assets\OrderAsset::register($this);

?>

<div id="order-view">
    <h1>
        <?= Yii::t('cart', 'Order') . ' #' . $order->uid; ?>
    </h1>

    <!--STATUS-->
    <div class="status">
        <h2>
            <?= Yii::t('cart', 'Order status'); ?>
        </h2>
        <p>
            <b><?= $order->orderStatus->translation->title; ?></b>
        </p>
        <p>
            <?= $order->orderStatus->translation->description; ?>
        </p>
    </div>

    <!--Products list-->
    <h2><?= Yii::t('cart', 'Products list'); ?></h2>
    <table class="table table-hover table-striped products-list">
        <tr>
            <th class="col-md-4 text-center"><?= Yii::t('cart', 'Title'); ?></th>
            <th class="col-md-1 text-center"><?= Yii::t('cart', 'Photo'); ?></th>
            <th class="col-md-2 text-center"><?= Yii::t('cart', 'Price'); ?></th>
            <th class="col-md-2 text-center"><?= Yii::t('cart', 'Count'); ?></th>
        </tr>
        <?php foreach ($order->orderProducts as $orderProduct): ?>
            <tr>
                <td class="text-center">
                    <?= Html::a($orderProduct->product->translation->title,
                        Url::to(['/shop/product/show', 'id' => $orderProduct->product->id])); ?>
                </td>
                <td class="text-center">
                    <?= Html::img($orderProduct->smallPhoto); ?>
                </td>
                <td class="text-center">
                    <?php if (!empty($orderProduct->price)) : ?>
                        <?= $orderProduct->price; ?>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?= $orderProduct->count; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!--DELIVERY METHOD-->
    <div class="delivery col-md-6">
        <h2><?= Yii::t('cart', 'Delivery method'); ?></h2>
        <p>
            <b><?= $order->deliveryMethod->translation->title; ?></b>
        </p>
        <p>
            <?= $order->deliveryMethod->translation->description; ?>
        </p>
    </div>

    <!--PAYMENT METHOD-->
    <div class="payment col-md-6">
        <?php if (Yii::$app->cart->enablePayment) : ?>
            <h2><?= Yii::t('cart', 'Payment method'); ?></h2>
            <p>
                <b><?= $order->paymentMethod->translation->title; ?></b>
            </p>
            <p>
                <?= $order->paymentMethod->translation->description; ?>
            </p>
        <?php endif; ?>
    </div>


</div>

