<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $availabilities xalberteinsteinx\shop\common\entities\ProductAvailability
 */

use rmrevin\yii\fontawesome\FA;
use xalberteinsteinx\shop\widgets\ManageButtons;
use bl\multilang\entities\Language;
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Availability statuses');

$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    $this->title
];

$currentLanguage = Language::getCurrent();
?>

<div class="box">

    <div class="box-title">
        <h1>
            <?= FA::i(FA::_TASKS) . ' ' . Html::encode($this->title) ?>
        </h1>

        <p>
            <?= Html::a(
                Html::tag('span', FA::i(FA::_USER_PLUS) . ' ' . Yii::t('shop', 'Add')),
                ['save', 'languageId' => $currentLanguage->id], ['class' => 'btn btn-primary btn-xs pull-right']) ?>
        </p>
    </div>

    <div class="box-content">

        <?php if (!empty($availabilities)) : ?>
            <div class="col-md-8 block-center">
                <table class="table">
                    <tr>
                        <th class="col-md-8"><?= \Yii::t('shop', 'Title'); ?></th>
                        <th class="col-md-4"><?= \Yii::t('shop', 'Control'); ?></th>
                    </tr>
                    <?php foreach ($availabilities as $availability) : ?>
                        <tr>
                            <td class="project-title">
                                <a href="<?= Url::toRoute(['save', 'id' => $availability->id, 'languageId' => $currentLanguage->id]); ?>">
                                    <?= $availability->translation->title; ?>
                                </a>
                            </td>
                            <td><?= ManageButtons::widget(['model' => $availability]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

