<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this                   yii\web\View
 * @var $languages[]            bl\multilang\entities\Language
 * @var $selectedLanguage       bl\multilang\entities\Language
 * @var $maxPosition            integer
 * @var $category               \sointula\shop\common\entities\Category
 * @var $categories             \sointula\shop\common\entities\Category[]
 * @var $categoryTranslation    \sointula\shop\common\entities\CategoryTranslation
 */

use marqu3s\summernote\Summernote;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<!--TABS-->
<?= $this->render('_tabs', ['selectedLanguage' => $selectedLanguage, 'category' => $category]); ?>

<!--CONTENT-->
<div class="box padding20">

    <?php $addForm = ActiveForm::begin([
        'method' => 'post',
        'action' => [
            'category/add-basic',
            'id' => $category->id,
            'languageId' => $selectedLanguage->id
        ],
        'options' => [
            'enctype' => 'multipart/form-data'
        ]]) ?>

    <header>
        <section class="title">
            <h1><?= \Yii::t('shop', 'Basic options'); ?></h1>
        </section>

        <section class="buttons">
            <?= Html::submitInput(\Yii::t('shop', 'Save'), ['class' => 'btn btn-xs btn-primary m-r-xs pull-right']); ?>

            <!--CANCEL BUTTON-->
            <a href="<?= Url::to(['/shop/category']); ?>">
                <?= Html::button(\Yii::t('shop', 'Cancel'), [
                    'class' => 'btn m-t-xs m-r-xs btn-danger btn-xs pull-right'
                ]); ?>
            </a>

            <!-- LANGUAGES -->
            <?= \sointula\shop\widgets\LanguageSwitcher::widget([
                'selectedLanguage' => $selectedLanguage,
            ]); ?>
        </section>
    </header>

    <div>
        <!-- NAME -->
        <?= $addForm->field($categoryTranslation, 'title', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->label(\Yii::t('shop', 'Name'))
        ?>

        <div class="row">
            <!-- PARENT CATEGORY -->
            <div class="col-md-6">
                <?=
                \sointula\shop\widgets\InputTree::widget([
                    'className' => \sointula\shop\common\entities\Category::className(),
                    'form' => $addForm,
                    'model' => $category,
                    'attribute' => 'parent_id',
                    'languageId' => $selectedLanguage->id
                ]);
                ?>
            </div>

            <!-- SHOW -->
            <?php $category->show = ($category->isNewRecord) ? true : $category->show; ?>
            <?= $addForm->field($category, 'show', [
                'inputOptions' => [
                    'class' => 'form-control'
                ]
            ])->checkbox(['class' => 'i-checks', 'checked ' => ($category->show) ? '' : false]);
            ?>

            <!-- ADDITIONAL PRODUCTS -->
            <?= $addForm->field($category, 'additional_products', [
                'inputOptions' => [
                    'class' => 'form-control'
                ]
            ])->checkbox(['class' => 'i-checks', 'checked ' => ($category->additional_products) ? '' : false]);
            ?>
        </div>

        <!-- DESCRIPTION -->
        <?= $addForm->field($categoryTranslation, 'description', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->widget(Summernote::className())->label(\Yii::t('shop', 'Description'))
        ?>

        <!-- SORT ORDER -->
        <?= $addForm->field($category, 'position', [
            'inputOptions' => [
                'class' => 'form-control'
            ]])->textInput([
            'type' => 'number',
            'max' => $maxPosition,
            'min' => 1,
        ]); ?>

    </div>

    <?php $addForm::end(); ?>

</div>
