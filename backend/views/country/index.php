<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $countries ProductCountry[]
 */
use xalberteinsteinx\shop\common\entities\ProductCountry;
use xalberteinsteinx\shop\widgets\ManageButtons;
use bl\multilang\entities\Language;
use yii\bootstrap\Html;

$this->title = \Yii::t('shop', 'List of countries');
?>

<div class="box">

    <div class="box-title">
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user-plus']) .
            Yii::t('shop', 'Add country'), ['save', 'languageId' => Language::getCurrent()->id], ['class' => 'btn btn-primary btn-xs pull-right']);
        ?>
        <h5>
            <i class="glyphicon glyphicon-list">
            </i>
            <?= Html::encode($this->title); ?>
        </h5>
    </div>

    <div class="box-content">
        <table class="table table-hover">
            <?php if (!empty($countries)): ?>
                <thead>
                <tr>
                    <th class="col-md-2"><?= \Yii::t('shop', 'Id'); ?></th>
                    <th class="col-md-8"><?= \Yii::t('shop', 'Title'); ?></th>
                    <th class="col-md-2"><?= \Yii::t('shop', 'Control'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($countries as $country) : ?>
                    <tr>
                        <td>
                            <?= $country->id; ?>
                        </td>
                        <td>
                            <?php if (!empty($country->translation->title)) : ?>
                                <?= $country->translation->title; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= ManageButtons::widget(['model' => $country]); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            <?php endif; ?>
        </table>
        <?php if (count($countries) > 5) : ?>
            <div class="row">
                <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user-plus']) .
                    Yii::t('shop', 'Add country'), ['save', 'languageId' => Language::getCurrent()->id], ['class' => 'btn btn-primary btn-xs pull-right']);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>
