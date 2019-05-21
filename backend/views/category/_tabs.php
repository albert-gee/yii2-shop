<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $category           \albertgeeca\shop\common\entities\Category
 * @var $selectedLanguage   \bl\multilang\entities\Language
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<header class="tabs">
    <ul class="nav nav-tabs">
        <li class="<?= Yii::$app->controller->action->id == 'add-basic' || Yii::$app->controller->action->id == 'save' ? 'tab active' : 'tab'; ?>">
            <?= Html::a(Yii::t('shop', 'Basic'), Url::to(['add-basic', 'id' => $category->id, 'languageId' => $selectedLanguage->id])); ?>
        </li>
        <li class="<?= Yii::$app->controller->action->id == 'add-seo' ? 'tab active' : 'tab'; ?> <?= $category->isNewRecord ? 'disabled' : ''; ?>">
            <?= Html::a(Yii::t('shop', 'SEO data'), $category->isNewRecord ? '' : Url::to(['add-seo', 'id' => $category->id, 'languageId' => $selectedLanguage->id]), ['class' => 'pjax']); ?>
        </li>
        <li class="<?= Yii::$app->controller->action->id == 'add-images' ? 'tab active' : 'tab'; ?> <?= $category->isNewRecord ? 'disabled' : ''; ?>">
            <?= Html::a(Yii::t('shop', 'Images'), $category->isNewRecord ? '' : Url::to(['add-images', 'categoryId' => $category->id, 'languageId' => $selectedLanguage->id]), ['class' => 'pjax']); ?>
        </li>
    </ul>
</header>
