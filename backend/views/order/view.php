<?php
use xalberteinsteinx\shop\common\entities\DeliveryMethod;
use xalberteinsteinx\shop\common\entities\Currency;
use bl\imagable\helpers\FileHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $model xalberteinsteinx\shop\common\entities\Order
 * @var $orderProducts xalberteinsteinx\shop\common\entities\OrderProduct
 * @var $statuses [] xalberteinsteinx\shop\common\entities\OrderStatus
 */

$this->title = Yii::t('cart', 'Order details');

$this->params['breadcrumbs'] = [
    Yii::t('cart', 'Orders'),
    [
        'label' => Yii::t('cart', 'Order list'),
        'url' => ['/shop/order'],
        'itemprop' => 'url'
    ],
    $this->title
];
?>
<div class="ibox">

    <div class="ibox-title">
        <h1><?= \Yii::t('cart', 'Order #') . $model->uid; ?></h1>
    </div>

    <?php if (Yii::$app->user->can('changeOrderStatus')) : ?>
        <div class="ibox-content">
            <!--CHANGE STATUS-->
            <h2>
                <?= Yii::t('cart', 'Order status'); ?>
            </h2>
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'status')->dropDownList(ArrayHelper::map($statuses, 'id', function ($model) {
                return $model->translation->title;
            }), ['options' => [$model->status => ['selected' => true]]]); ?>
            <?= Html::submitButton(Yii::t('cart', 'Change status'), ['class' => 'btn btn-xs btn-primary']); ?>
            <?= Html::a(Yii::t('cart', 'Close'), Url::toRoute('/shop/order'), ['class' => 'btn btn-xs btn-danger']) ?>
            <?php $form::end(); ?>
        </div>
    <?php endif; ?>

    <div class="ibox-content">
        <div class="row">
            <div class="col-md-6">
                <!--CUSTOMER DATA-->
                <h2>
                    <?= Yii::t('cart', 'Customer data'); ?>
                </h2>

                <?php if (!empty($model->user->profile->name)) : ?>
                    <p><b><?= \Yii::t('cart', 'Customer name'); ?>:</b> <?= $model->user->profile->name; ?></p>
                <?php endif; ?>
                <?php if (!empty($model->user->profile->surname)) : ?>
                    <p><b><?= \Yii::t('cart', 'Surname'); ?>:</b> <?= $model->user->profile->surname; ?></p>
                <?php endif; ?>
                <?php if (!empty($model->user->profile->patronymic)) : ?>
                    <p><b><?= \Yii::t('cart', 'Patronymic'); ?>:</b> <?= $model->user->profile->patronymic; ?></p>
                <?php endif; ?>

                <?php if (!empty($model->user->profile->info)) : ?>
                    <p><b><?= \Yii::t('cart', 'Customer name'); ?>:</b> <?= $model->user->profile->info; ?></p>
                <?php endif; ?>

                <?php if (!empty($model->user->profile->phone)) : ?>
                    <p><b><?= \Yii::t('cart', 'Phone number'); ?>:</b> <?= $model->user->profile->phone; ?></p>
                <?php endif; ?>

                <?php if (!empty($model->user->email)) : ?>
                    <p><b><?= \Yii::t('cart', 'Email'); ?>:</b> <?= $model->user->email; ?></p>
                <?php endif; ?>

                <!--PAYMENT METHOD-->
                <?php if (Yii::$app->cart->enablePayment === true && !empty($model->paymentMethod)) : ?>
                    <h2>
                        <?= Yii::t('cart', 'Payment method'); ?>
                    </h2>
                    <div class="col-md-2">
                        <?php if (!empty($model->paymentMethod->image)) : ?>
                            <?= Html::img(
                                '/images/payment/' . FileHelper::getFullName(\Yii::$app->shop_imagable->get('payment', 'small', $model->paymentMethod->image)),
                                ['class' => '', 'style' => 'width: 100%;']); ?>
                        <?php endif; ?>
                    </div>
                    <p class="col-md-10">
                        <?= (!empty($model->paymentMethod)) ? $model->paymentMethod->translation->title : ''; ?>
                    </p>
                <?php endif; ?>
            </div>

            <!--DELIVERY METHOD-->
            <div class="col-md-6">
                <?php if (!empty($model->deliveryMethod)): ?>
                    <div class="col-md-4">
                        <?= Html::img($model->deliveryMethod->smallLogo); ?>
                    </div>
                    <div class="col-md-8">
                        <h2>
                            <?= Yii::t('cart', 'Delivery method'); ?>
                        </h2>
                        <?php if (!empty($model->deliveryMethod->translation->title)) : ?>
                            <p>
                                <?= Html::tag('b', $model->deliveryMethod->translation->title); ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($model->deliveryMethod->show_address_or_post_office == DeliveryMethod::SHOW_POST_OFFICE_FIELD) : ?>
                            <?php if (!empty($model->delivery_post_office)) : ?>
                                <p><?= Yii::t('cart', 'Post office') . ': #' . $model->delivery_post_office; ?></p>
                            <?php endif; ?>
                        <?php elseif ($model->deliveryMethod->show_address_or_post_office == DeliveryMethod::SHOW_ADDRESS_FIELDS) : ?>
                            <!--Address-->
                            <p><b><?= Yii::t('cart', 'Country'); ?>
                                    :</b> <?= (!empty($model->address->country)) ? $model->address->country : ''; ?></p>
                            <p><b><?= Yii::t('cart', 'Region'); ?>
                                    :</b> <?= (!empty($model->address->region)) ? $model->address->region : ''; ?></p>
                            <p><b><?= Yii::t('cart', 'City'); ?>
                                    :</b> <?= (!empty($model->address->city)) ? $model->address->city : ''; ?></p>
                            <p><b><?= Yii::t('cart', 'Street'); ?>
                                    :</b> <?= (!empty($model->address->street)) ? $model->address->street : ''; ?></p>
                            <p><b><?= Yii::t('cart', 'House'); ?>
                                    :</b> <?= (!empty($model->address->house)) ? $model->address->house : ''; ?></p>
                            <p><b><?= Yii::t('cart', 'Apartment'); ?>
                                    :</b> <?= (!empty($model->address->apartment)) ? $model->address->apartment : ''; ?>
                            </p>
                            <p><b><?= Yii::t('cart', 'Zip'); ?>
                                    :</b> <?= (!empty($model->address->zipcode)) ? $model->address->zipcode : ''; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!--PRODUCT LIST-->
    <div class="ibox-content col-md-12">
        <h2>
            <?= Yii::t('cart', 'Product list'); ?>
        </h2>

        <table class="table table-hover table-striped table-bordered">
            <tr>
                <th>#</th>
                <th>
                    <?= Yii::t('cart', 'SKU'); ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Product title'); ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Count'); ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Price'); ?>
                </th>
                <th>
                    <?= Yii::t('cart', 'Delete'); ?>
                </th>
            </tr>

            <?php $i = 0;
            foreach ($orderProducts as $orderProduct) : ?>
                <tr>
                    <td><?= ++$i; ?></td>
                    <td>
                        <?= $orderProduct->product->sku ?? ''; ?>
                    </td>
                    <td>
                        <?= $orderProduct->product->translation->title ?? ''; ?>
                        <?= (!empty($orderProduct->priceTitle)) ?
                            Html::tag('i', '(' . $orderProduct->priceTitle . ')') : ''; ?>

                        <!--Additional products-->
                        <?php if (!empty($orderProduct->orderProductAdditionalProducts)): ?>
                            <ul>
                                <?php foreach ($orderProduct->orderProductAdditionalProducts as $orderProductAdditionalProduct): ?>
                                    <li>
                                        <?= Html::a(
                                            $orderProductAdditionalProduct->additionalProduct->translation->title,
                                            Url::to(['/shop/product/save', 'id' => $orderProductAdditionalProduct->additionalProduct->id,
                                                'languageId' => \bl\multilang\entities\Language::getCurrent()->id])
                                        ) .
                                        ', ' . $orderProductAdditionalProduct->number . ' ' . \Yii::t('cart', 'pieces.') .
                                        ' - ' . \Yii::$app->formatter->asCurrency($orderProductAdditionalProduct->additionalProduct->discountPrice  * Currency::currentCurrency()); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $orderProduct->count; ?>
                    </td>
                    <td>
                        <?= (floor($orderProduct->price * Currency::currentCurrency()) ?? 0) . ' грн'; ?>
                    </td>
                    <td>
                        <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', Url::toRoute(['delete-product', 'id' => $orderProduct->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger pull-right pjax']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>