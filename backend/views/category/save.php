<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $viewName string
 * @var $params array
 */

use yii\widgets\Pjax;
use yii\helpers\{
    Html, Url
};
use xalberteinsteinx\shop\backend\assets\EditProductAsset;

EditProductAsset::register($this);

$this->title = ($params['category']->isNewRecord) ? \Yii::t('shop', 'Add new category') : \Yii::t('shop', 'Edit category');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('shop', 'Shop'),
        'url' => ['/seo/static/save-page', 'page_key' => 'shop', 'languageId' => $params['selectedLanguage']->id],
        'itemprop' => 'url'
    ],
    [
        'label' => Yii::t('shop', 'Categories'),
        'url' => ['/shop/category'],
        'itemprop' => 'url'
    ],
    $params['category']->translation->title ?? ''
];
?>

<div class="tabs-container">

    <?php Pjax::begin([
        'linkSelector' => '.pjax',
        'enablePushState' => true,
        'timeout' => 10000
    ]);
    ?>
    <!--TABS-->
    <ul class="nav nav-tabs">
        <li class="<?= Yii::$app->controller->action->id == 'add-basic' || Yii::$app->controller->action->id == 'save' ? 'tab active' : 'tab'; ?>">
            <?= Html::a(Yii::t('shop', 'Basic'), Url::to(['add-basic', 'id' => $params['category']->id, 'languageId' => $params['selectedLanguage']->id])); ?>
        </li>
        <li class="<?= Yii::$app->controller->action->id == 'add-seo' ? 'tab active' : 'tab'; ?> <?= $params['category']->isNewRecord ? 'disabled' : ''; ?>">
            <?= Html::a(Yii::t('shop', 'SEO data'), $params['category']->isNewRecord ? '' : Url::to(['add-seo', 'id' => $params['category']->id, 'languageId' => $params['selectedLanguage']->id]), ['class' => 'pjax']); ?>
        </li>
        <li class="<?= Yii::$app->controller->action->id == 'add-images' ? 'tab active' : 'tab'; ?> <?= $params['category']->isNewRecord ? 'disabled' : ''; ?>">
            <?= Html::a(Yii::t('shop', 'Images'), $params['category']->isNewRecord ? '' : Url::to(['add-images', 'categoryId' => $params['category']->id, 'languageId' => $params['selectedLanguage']->id]), ['class' => 'pjax']); ?>
        </li>
        <li class="<?= Yii::$app->controller->action->id == 'select-filters' ? 'tab active' : 'tab'; ?> <?= $params['category']->isNewRecord ? 'disabled' : ''; ?>">
            <?= Html::a(Yii::t('shop', 'Filters'), $params['category']->isNewRecord ? '' : Url::to(['select-filters', 'categoryId' => $params['category']->id, 'languageId' => $params['selectedLanguage']->id]), ['class' => 'pjax']); ?>
        </li>
    </ul>

    <!--CONTENT-->
    <div class="box-content">

        <!-- LANGUAGES -->
        <?= \xalberteinsteinx\shop\widgets\LanguageSwitcher::widget([
            'selectedLanguage' => $params['selectedLanguage'],
        ]); ?>

        <!--CANCEL BUTTON-->
        <a href="<?= Url::to(['/shop/category']); ?>">
            <?= Html::button(\Yii::t('shop', 'Cancel'), [
                'class' => 'btn m-t-xs m-r-xs btn-danger btn-xs pull-right'
            ]); ?>
        </a>

        <!--TAB VIEW-->
        <?= $this->render($viewName, $params); ?>
    </div>
    <?php Pjax::end(); ?>
</div>

