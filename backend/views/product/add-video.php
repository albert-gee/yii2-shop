<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $product            Product
 * @var $selectedLanguage   Language
 * @var $video_form         ProductVideo
 * @var $videos             ProductVideo
 * @var $video_form_upload  ProductVideoForm
 */

use xalberteinsteinx\shop\backend\components\form\ProductVideoForm;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\common\entities\ProductVideo;
use bl\multilang\entities\Language;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>


<!--Tabs-->
<?= $this->render('_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $selectedLanguage
]); ?>

<div class="box padding20">

    <?php Pjax::begin(); ?>

    <?php $addVideoForm = ActiveForm::begin([
        'action' => [
            'product/add-video',
            'id' => $product->id,
            'languageId' => $selectedLanguage->id
        ],
        'method' => 'post',
        'options' => [
            'data-pjax' => true
        ]
    ]);
    ?>
    <h2><?= \Yii::t('shop', 'Video'); ?></h2>

    <table class="table table-bordered">
        <tr class="text-center">
            <td class="col-md-2">
                <strong>
                    <?= \Yii::t('shop', 'Add from service'); ?>
                </strong>
            </td>
            <td class="col-md-4">
                <?= $addVideoForm->field($video, 'resource')->dropDownList(
                    [
                        'youtube' => 'YouTube',
                        'vimeo' => 'Vimeo'
                    ]
                )->label(false); ?>
            </td>
            <td class="col-md-4">
                <?= $addVideoForm->field($video, 'file_name')->textInput(['placeholder' => \Yii::t('shop', 'Link to video')])->label(false); ?>
            </td>
            <td class="col-md-2">
                <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary']) ?>
            </td>
        </tr>
    </table>
    <?php $addVideoForm->end(); ?>

    <?php $uploadVideoForm = ActiveForm::begin([
        'action' => [
            'product/add-video',
            'id' => $product->id,
            'languageId' => $selectedLanguage->id
        ],
        'method' => 'post',
        'options' => [
            'data-pjax' => true,
            'enctype' => 'multipart/form-data'
        ]
    ]);
    ?>
    <table class="table table-bordered">
        <tr class="text-center">
            <td class="col-md-2">
                <strong>
                    <?= \Yii::t('shop', 'Upload'); ?>
                </strong>
            </td>
            <td class="col-md-4">
            </td>
            <td class="col-md-4">
                <?= $uploadVideoForm->field($video_form_upload, 'file_name')->fileInput()->label(false); ?>
            </td>
            <td class="col-md-2">
                <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary']) ?>
            </td>
        </tr>
    </table>
    <?php $uploadVideoForm->end(); ?>
    <p>
        <i>
            <?= '*' . \Yii::t('shop', 'The maximum file size limit for uploads is') . ' ' .
            (int)(ini_get('upload_max_filesize')) . 'Mb'; ?>
        </i>
    </p>

    <table class="table table-bordered">
        <thead class="thead-inverse">

        <?php if (!empty($product->videos)) : ?>
            <tr>
                <th class="text-center col-md-2">
                    <?= \Yii::t('shop', 'Resource'); ?>
                </th>
                <th class="text-center col-md-4">
                    <?= \Yii::t('shop', 'ID'); ?>
                </th>
                <th class="text-center col-md-4">
                    <?= \Yii::t('shop', 'Preview'); ?>
                </th>
                <th class="text-center col-md-2">
                    <?= \Yii::t('shop', 'Delete'); ?>
                </th>
            </tr>
        <?php endif; ?>
        </thead>

        <tbody>
        <?php foreach ($product->videos as $video) : ?>
            <tr>
                <td class="text-center">
                    <?= $video->resource; ?>
                </td>
                <td class="text-center">
                    <?= $video->file_name; ?>
                </td>
                <td class="text-center">
                    <?php if ($video->resource == 'youtube') : ?>
                        <iframe width="100%" height="200" src="https://www.youtube.com/embed/<?= $video->file_name; ?>"
                                frameborder="0" allowfullscreen></iframe>
                    <?php elseif ($video->resource == 'vimeo') : ?>
                        <iframe src="https://player.vimeo.com/video/<?= $video->file_name; ?>" width="100%" height="200"
                                frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    <?php elseif ($video->resource == 'videofile') : ?>
                        <video width="100%" height="200" controls>
                            <source src="/video/<?= $video->file_name; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <a href="<?= Url::toRoute(['delete-video', 'id' => $video->id, 'languageId' => $selectedLanguage->id]); ?>"
                       class="media glyphicon glyphicon-remove text-danger btn btn-default btn-sm"></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php Pjax::end(); ?>

</div>