<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $availabilities xalberteinsteinx\shop\common\entities\ProductAvailability
 */

use xalberteinsteinx\shop\widgets\ManageButtons;
use bl\multilang\entities\Language;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = Yii::t('shop', 'Product availabilities');

$currentLanguage = Language::getCurrent();
?>

<div class="panel panel-default">

    <div class="panel-heading">
        <i class="glyphicon glyphicon-list"></i>
        <?= Html::encode($this->title) ?>
    </div>

    <div class="panel-body">

        <p>
            <?= Html::a(Yii::t('shop', 'Add'), ['save', 'languageId' => $currentLanguage->id], ['class' => 'btn btn-primary btn-xs pull-right']) ?>
        </p>

        <?php if (!empty($availabilities)) : ?>
            <table class="table table-hover">
                <tr>
                    <th class="col-lg-10"><?= \Yii::t('shop', 'Title'); ?></th>
                    <th class="col-lg-2"><?= \Yii::t('shop', 'Control'); ?></th>
                </tr>
                <?php foreach ($availabilities as $availability ) : ?>
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
        <?php endif; ?>

        <p>
            <?= Html::a(Yii::t('shop', 'Add'), ['save', 'languageId' => $currentLanguage->id], ['class' => 'btn btn-primary btn-xs pull-right']) ?>
        </p>

    </div>
</div>

