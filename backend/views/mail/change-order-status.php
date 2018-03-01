<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $model \sointula\shop\common\entities\Order
 * @var $model->orderStatus \sointula\shop\common\entities\OrderStatus
 * @var $address \sointula\shop\common\components\user\models\UserAddress
 */

use yii\bootstrap\Html;

$model->delivery_post_office = (!empty($model->delivery_post_office)) ? $model->delivery_post_office : '';
$fullAddress = (!empty($model->address_id)) ?
    Html::ul([
        Html::tag('strong', Yii::t('cart', 'Country')) . ': ' . $address->country,
        Html::tag('strong', Yii::t('cart', 'Region')) . ': ' . $address->region,
        Html::tag('strong', Yii::t('cart', 'City')) . ': ' . $address->city,
        Html::tag('strong', Yii::t('cart', 'House')) . ': ' . $address->house,
        Html::tag('strong', Yii::t('cart', 'Apartment')) . ': ' . $address->apartment,
        Html::tag('strong', Yii::t('cart', 'Zip')) . ': ' . $address->zipcode,
    ]) : '';
?>

<!--TITLE-->
<?php if (!empty($model->orderStatus->translation->title)) : ?>
    <?= Html::tag('h1', Yii::t('cart', 'Your order') . ' #' . $model->uid . Yii::t('cart', ' is ') .
        mb_strtolower($model->orderStatus->translation->title)); ?>
<?php endif; ?>

<!--MAIL BODY-->
<?php if (!empty($model->orderStatus->translation->mail)) : ?>
<?= strtr($model->orderStatus->translation->mail, [
        '{order_id}' => $model->uid,
        '{created_at}' => Yii::$app->formatter->asDatetime($model->creation_time)
    ]); ?>
<?php endif; ?>
