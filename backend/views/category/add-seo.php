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

<?php $addForm = ActiveForm::begin(['method' => 'post', 'options' => []]) ?>
<?= Html::submitInput(\Yii::t('shop', 'Save'), ['class' => 'btn btn-xs btn-primary m-r-xs pull-right']); ?>

<h2><?= \Yii::t('shop', 'SEO options'); ?></h2>

<?= $addForm->field($categoryTranslation, 'seoUrl', [
    'template' => "{label}\n
                        <div class='input-group'>
                            {input}\n
                            <span class='input-group-btn'>
                                <button id='getSeoUrl' class='btn btn-primary' type='button'>
                                    <span class='glyphicon glyphicon-refresh' aria-hidden='true'></span>
                                </button>
                            </span>
                        </div>\n{hint}\n{error}",
    'inputOptions' => [
        'class' => 'form-control'
    ]
])->label('SEO URL')
?>

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

<div class="ibox">
    <!--CANCEL BUTTON-->
    <a href="<?= Url::to(['/shop/category']); ?>">
        <?= Html::button(\Yii::t('shop', 'Cancel'), [
            'class' => 'btn btn-danger btn-xs pull-right'
        ]); ?>
    </a>
    <?= Html::submitInput(\Yii::t('shop', 'Save'), ['class' => 'btn btn-xs btn-primary m-r-xs pull-right']); ?>
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
$("button#getSeoUrl").click(getSeoUrl);
') ?>
