<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $productId integer
 * @var $image_form ProductImageForm
 * @var $selectedLanguage \bl\multilang\entities\Language
 */

use xalberteinsteinx\shop\backend\components\form\ProductImageForm;
use xalberteinsteinx\shop\common\entities\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<?php Pjax::begin();?>

<?php $addImageForm = ActiveForm::begin([
    'action' => [
        'product/add-image',
        'id' => $productId,
        'languageId' => $selectedLanguage->id
    ],
    'method' => 'post',
    'options' => [
        'class' => 'tab-content',
        'data-pjax' => true
    ]
]);
?>

<h2><?= \Yii::t('shop', 'Images'); ?></h2>

<table class="table table-bordered">
    <tbody>

    <tr>
        <td class="col-md-3 text-center" colspan="2">
            <strong>
                <?= \Yii::t('shop', 'Add from web'); ?>
            </strong>
        </td>
        <td class="col-md-4">
            <?= $addImageForm->field($image_form, 'link')->textInput([
                'placeholder' => Yii::t('shop', 'Image link')
            ])->label(false); ?>
        </td>
        <td class="col-md-3">
            <?= $addImageForm->field($image_form, 'alt1')->textInput(['placeholder' => \Yii::t('shop', 'Alternative text')])->label(false); ?>
        </td>
        <td class="col-md-2 text-center">
            <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary']) ?>
        </td>
    </tr>
    <tr>
        <td class="text-center" colspan="2">
            <strong>
                <?= \Yii::t('shop', 'Upload'); ?>
            </strong>
        </td>
        <td>
            <?= $addImageForm->field($image_form, 'image')->fileInput()->label(false); ?>
        </td>
        <td class="text-center">
            <?= $addImageForm->field($image_form, 'alt2')->textInput(['placeholder' => \Yii::t('shop', 'Alternative text')])->label(false); ?>
        </td>
        <td class="text-center">
            <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary']) ?>
        </td>
    </tr>
    <tr>
        <td colspan="5">
            <p>
                <i>
                    <?= '*' . \Yii::t('shop', 'The maximum file size limit for uploads is') . ' ' .
                    (int)(ini_get('upload_max_filesize')) . 'Mb.'; ?>
                    <?= \Yii::t('shop', 'Upload only optimized and lightweight images. This will speed up the website.'); ?>
                </i>
            </p>
        </td>
    </tr>
    <tr>
        <td class="col-md-1"></td>
        <th class="text-center col-md-2">
            <?= \Yii::t('shop', 'Image preview'); ?>
        </th>
        <th class="text-center col-md-4">
            <?= \Yii::t('shop', 'Image URL'); ?>
        </th>
        <th class="text-center col-md-3">
            <?= \Yii::t('shop', 'Alt'); ?>
        </th>
        <th class="text-center col-md-2">
            <?= \Yii::t('shop', 'Control'); ?>
        </th>
    </tr>
    <?php if (!empty($images)) : ?>
        <?php foreach ($images as $image) : ?>
            <tr>
                <td>
                    <?= Html::a(
                        '',
                        Url::toRoute(['image-up', 'id' => $image->id, 'languageId' => $selectedLanguage->id]),
                        [
                            'class' => 'pjax fa fa-chevron-up'
                        ]
                    ) .
                    $image->position .
                    Html::a(
                        '',
                        Url::toRoute(['image-down', 'id' => $image->id, 'languageId' => $selectedLanguage->id]),
                        [
                            'class' => 'pjax fa fa-chevron-down'
                        ]
                    );
                    ?>
                </td>
                <td class="text-center">
                    <img data-toggle="modal" data-target="#menuItemModal-<?= $image->id ?>"
                         src="<?= $image->small; ?>"
                         class="thumb">
                    <!-- Modal -->
                    <div id="menuItemModal-<?= $image->id ?>" class="modal fade" role="dialog">
                        <img style="display: block" class="modal-dialog"
                             src="<?= $image->thumb; ?>">
                    </div>
                </td>
                <td class="text-center">
                    <div class="image-link">
                        <p>
                            <i class="fa fa-external-link" aria-hidden="true"></i>
                            <?= str_replace(Yii::$app->homeUrl, '', Url::home(true)) . $image->big; ?>
                        </p>
                    </div>
                </td>
                <td class="text-center">
                    <?= $image->getTranslation($selectedLanguage->id)->alt ?? ''; ?>
                </td>
                <td class="text-center">
                    <a href="<?= Url::toRoute(['edit-image', 'id' => $image->id, 'languageId' => $selectedLanguage->id]); ?>"
                       class="glyphicon glyphicon-edit btn btn-default btn-sm pjax"></a>

                    <a href="<?= Url::toRoute(['delete-image', 'id' => $image->id, 'languageId' => $selectedLanguage->id]); ?>"
                       class="glyphicon glyphicon-remove text-danger btn btn-default btn-sm pjax"></a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

<?php $addImageForm->end(); ?>
<?php Pjax::end();?>

