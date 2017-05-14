<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $languages [] bl\multilang\entities\Language
 * @var $selectedLanguage bl\multilang\entities\Language
 * @var $maxPosition integer
 * @var $category \xalberteinsteinx\shop\common\entities\Category
 * @var $categories \xalberteinsteinx\shop\common\entities\Category[]
 * @var $categoryTranslation \xalberteinsteinx\shop\common\entities\CategoryTranslation
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<!--TABS-->
<?= $this->render('_tabs', ['selectedLanguage' => $selectedLanguage, 'category' => $category]); ?>

<!--CONTENT-->
<div class="box padding20">


<?php $addForm = ActiveForm::begin(['method' => 'post', 'options' => []]) ?>

    <header>
        <section class="title">
            <h1><?= \Yii::t('shop', 'SEO options'); ?></h1>
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
            <?= \xalberteinsteinx\shop\widgets\LanguageSwitcher::widget([
                'selectedLanguage' => $selectedLanguage,
            ]); ?>

        </section>

    </header>

    <div>

        <div class="seo-url">
            <?= $addForm->field($categoryTranslation, 'seoUrl', [
                'inputOptions' => [
                    'class' => 'form-control'
                ],
            ])->label('SEO URL')
            ?>
            <?= Html::button(\Yii::t('shop', 'Generate'), [
                'id' => 'generate-seo-url',
                'class' => 'btn btn-primary btn-in-input',
                'url' => Url::to('generate-seo-url')
            ]); ?>
        </div>

        <?= $addForm->field($categoryTranslation, 'seoTitle', [
            'inputOptions' => [
                'class' => 'form-control'
            ]
        ])->label(\Yii::t('shop', 'SEO title'))
        ?>
        <?= $addForm->field($categoryTranslation, 'seoDescription')->textarea(['rows' => 3])->label(\Yii::t('shop', 'SEO description'));
        ?>
        <?= $addForm->field($categoryTranslation, 'seoKeywords')->textarea(['rows' => 3])->label(\Yii::t('shop', 'SEO keywords'))
        ?>

    </div>

<?php $addForm::end(); ?>

<?php
$this->registerJs('
function getSeoUrl() {
        var $name = $("#articletranslation-name");
        $.ajax({
            type: "GET",
            url: "/admin/shop/category/get-seo-url",
            data: 
            {
                "categoryId": ' . $categoryTranslation->category_id . ',
                "languageId": ' . $categoryTranslation->language_id . '
            },
            success: function (data) {
                $("#categorytranslation-seourl").val(data);
            }
        });
    }
$("button#generate-seo-url").click(getSeoUrl);
') ?>
