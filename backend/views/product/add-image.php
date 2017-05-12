<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $product            Product
 * @var $image_form         ProductImageForm
 * @var $selectedLanguage   \bl\multilang\entities\Language
 * @var $images             \xalberteinsteinx\shop\common\entities\ProductImage[]
 * @var $modifiedElement    integer|null
 */

use rmrevin\yii\fontawesome\FA;
use xalberteinsteinx\shop\backend\components\form\ProductImageForm;
use xalberteinsteinx\shop\common\entities\Product;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$productId = $product->id;
?>

<!--Tabs-->
<?= $this->render('_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $selectedLanguage
]); ?>

<div class="box padding20">

    <?php Pjax::begin([
        'id' => 'p-product-image-' . $productId,
        'enablePushState' => false,
        'enableReplaceState' => false,
    ]); ?>

    <?php $addImageForm = ActiveForm::begin([
        'action' => [
            'product/add-image',
            'id' => $productId,
            'languageId' => $selectedLanguage->id
        ],
        'method' => 'post',
        'options' => [
            'id' => 'product-image-' . $productId,
            'data-pjax' => true
        ]
    ]);
    ?>

    <h2><?= \Yii::t('shop', 'Images'); ?></h2>

    <table class="table">
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
                <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary pjax']) ?>
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
                <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary pjax']) ?>
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
        </tbody>
    </table>
    <?php $addImageForm->end(); ?>

    <table class="table">
        <tbody>
        <tr>
            <th class="col-md-1"></th>
            <th class="text-center col-md-2">
                <p>
                    <?= \Yii::t('shop', 'Image preview'); ?>
                </p>
            </th>
            <th class="text-center col-md-4">
                <p>
                    <?= \Yii::t('shop', 'Image URL'); ?>
                </p>
            </th>
            <th class="text-center col-md-3">
                <p>
                    <?= \Yii::t('shop', 'Alt'); ?>
                </p>
            </th>
            <th class="text-center col-md-2">
                <p>
                    <?= \Yii::t('shop', 'Control'); ?>
                </p>
            </th>
        </tr>
        <?php if (!empty($images)) : ?>
            <?php foreach ($images as $image) : ?>
                <tr class="<?= ($modifiedElement == $image->id) ? 'modifiedElement' : ''; ?>">
                    <td>
                        <?= Html::a(
                            '',
                            Url::toRoute(['image-up', 'id' => $image->id, 'languageId' => $selectedLanguage->id]),
                            [
                                'class' => 'fa fa-chevron-up'
                            ]
                        ) .
                        $image->position .
                        Html::a(
                            '',
                            Url::toRoute(['image-down', 'id' => $image->id, 'languageId' => $selectedLanguage->id]),
                            [
                                'class' => 'fa fa-chevron-down'
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
                        <?php $editImageAltForm = ActiveForm::begin([
                            'id' => 'edit-image-alt-' . $image->id,
                            'action' => [
                                'product/edit-image',
                                'id' => $image->id,
                                'languageId' => $selectedLanguage->id
                            ],
                            'method' => 'post',
                            'options' => [
                                'id' => 'edit-product-image-' . $productId,
                                'data-pjax' => true
                            ]
                        ]);
                        ?>

                        <?= $editImageAltForm->field($image->getTranslation($selectedLanguage->id), 'alt', [
                            'inputOptions' => [
                                'class' => 'form-control'
                            ],
                        ])->label(false); ?>

                        <?= Html::submitButton(FA::i(FA::_EDIT), [
                            'class' => 'btn btn-primary btn-in-input',
                        ]); ?>

                        <?php $editImageAltForm->end(); ?>

                    </td>
                    <td class="text-center">

                        <a href="<?= Url::toRoute(['delete-image', 'id' => $image->id, 'languageId' => $selectedLanguage->id]); ?>"
                           class="btn btn-xs btn-danger"><?= FA::i(FA::_REMOVE); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <?php Pjax::end(); ?>

</div>