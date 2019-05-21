<?php
use bl\imagable\helpers\FileHelper;
use bl\multilang\entities\Language;
use rmrevin\yii\fontawesome\FA;
use albertgeeca\shop\widgets\ManageButtons;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model albertgeeca\shop\common\entities\PaymentMethod */

$this->title = Yii::t('payment', 'Payment Methods');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
    <div class="box-title">
        <h1>
            <?= FA::i(FA::_MONEY) . ' ' . $this->title; ?>
        </h1>
        <p class="col-md-6">
            <?= Html::a(Yii::t('payment', 'Add payment method'),
                Url::toRoute(['save', 'languageId' => Language::getCurrent()->id]),
                ['class' => 'btn btn-xs btn-primary pull-right']) ?>
        </p>
    </div>

    <div class="box-content">
        <table class="table">
            <tr>
                <th class="">
                    <?= Yii::t('payment', 'Title'); ?>
                </th>
                <th class="col-md-2">
                    <?= Yii::t('payment', 'Logo'); ?>
                </th>
                <th class="col-md-2 text-center">
                    <?= Yii::t('payment', 'Control'); ?>
                </th>
            </tr>
            <?php foreach ($model as $paymentMethod) : ?>
                <tr>
                    <td>
                        <?= Html::a($paymentMethod->translation->title,
                            Url::to(['save', 'id' => $paymentMethod->id, 'languageId' => Language::getCurrent()->id])); ?>
                    </td>
                    <td>
                        <?php if (!empty($paymentMethod->image)) : ?>
                            <?= Html::a(Html::img(
                                '/images/payment/' . FileHelper::getFullName(\Yii::$app->shop_imagable->get('payment', 'small', $paymentMethod->image)),
                                ['class' => '']),
                                Url::to(['save', 'id' => $paymentMethod->id, 'languageId' => Language::getCurrent()->id]));
                            ?>
                        <?php endif ;?>
                    </td>
                    <td>
                        <?= ManageButtons::widget(['model' => $paymentMethod]); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>