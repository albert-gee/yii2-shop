<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $searchModel albertgeeca\shop\common\entities\SearchCategory
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use rmrevin\yii\fontawesome\FA;
use albertgeeca\shop\backend\assets\CategoriesIndexAsset;
use albertgeeca\shop\common\entities\Category;
use bl\multilang\entities\Language;
use yii\helpers\Html;

$this->title = Yii::t('shop', 'Categories');
$this->params['breadcrumbs'] = [
    Yii::t('shop', 'Shop'),
    $this->title
];
CategoriesIndexAsset::register($this);
?>

<div class="box">

    <div class="box-title">
        <h1>
            <?= FA::i(FA::_TAGS) . ' ' . Html::encode($this->title); ?>
        </h1>

        <?= Html::a(FA::i(FA::_USER_PLUS) .
            Yii::t('shop', 'Create category'), ['save', 'languageId' => Language::getCurrent()->id], ['class' => 'btn btn-primary btn-xs pull-right']);
        ?>
    </div>

    <div class="box-content">
        <?= \albertgeeca\shop\widgets\TreeWidget::widget([
            'className' => Category::className(),
            'isGrid' => true,
            'appName' => '/admin',
            'downIconClass' => 'fa fa-plus',
            'upIconClass' => 'fa fa-minus',
        ]); ?>

    </div>

    <div class="box-footer">
        <div></div>
        <?= Html::a(Html::tag('i', '', ['class' => 'fa fa-user-plus']) .
            Yii::t('shop', 'Create category'), ['save', 'languageId' => Language::getCurrent()->id],
            ['class' => 'btn btn-primary btn-xs pull-right']);
        ?>
    </div>
</div>