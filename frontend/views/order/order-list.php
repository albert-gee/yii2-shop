<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $userOrders \bl\cms\cart\models\Order
 */


use yii\helpers\Html;
use yii\helpers\Url;

\bl\cms\cart\frontend\assets\OrderAsset::register($this);
?>

<div id="user-orders">

    <?php foreach ($userOrders as $userOrder): ?>
        <?php $orderLink = Html::a('', Url::to(['/cart/order/view', 'id' => $userOrder->id])); ?>
        <div class="user-order">
            <?= $orderLink; ?>
            <!--IMAGES-->
            <div class="images">
                <?php if (!empty($userOrder->orderProducts[0]->smallPhoto)): ?>
                    <div class="img" style="background-image: url(<?= $userOrder->orderProducts[0]->smallPhoto; ?>);">
                        <?= $orderLink; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($userOrder->orderProducts[1]->smallPhoto)): ?>
                    <div class="img" style="background-image: url(<?= $userOrder->orderProducts[1]->smallPhoto; ?>);">
                        <?= $orderLink; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!--INFO-->
            <div class="info">
                <header>
                    <div class="status">
                        <p style="background-color: <?= $userOrder->orderStatus->color; ?>">
                            <?= $userOrder->orderStatus->translation->title; ?>
                        </p>
                    </div>
                    <div class="uid">
                        <h2>
                            <?= '#' . $userOrder->uid; ?>
                        </h2>
                        <p class="date">
                            <?= Yii::$app->formatter->asDatetime($userOrder->confirmation_time); ?>
                        </p>
                    </div>
                </header>

                <div class="body">

                    <!--Product list-->
                    <div class="product-list">
                        <ul>
                            <?php for ($i = 0; $i <= 3; $i++): ?>
                                <?php if (!empty($userOrder->orderProducts[$i])): ?>
                                    <li><?= $userOrder->orderProducts[$i]->product->translation->title; ?></li>
                                <?php else: ?>
                                    <?php break; ?>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    </div>

                    <!--SHIPMENT AND DELIVERY-->
                    <div class="shipment-delivery">
                        <img src="<?= $userOrder->deliveryMethod->smallLogo; ?>" alt="<?= (!empty($userOrder->deliveryMethod->translation->title)) ? $userOrder->deliveryMethod->translation->title : '';?>">
                        <img src="<?= $userOrder->paymentMethod->smallLogo; ?>" alt="<?= (!empty($userOrder->paymentMethod->translation->title)) ? $userOrder->paymentMethod->translation->title : '';?>">
                    </div>
                </div>

            </div>


        </div>

    <?php endforeach; ?>

</div>