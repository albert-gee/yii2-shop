<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $fileList ProductFile[]
 * @var $fileModel ProductFile
 * @var $fileTranslationModel ProductFileTranslation
 * @var $product Product
 * @var Language[] $languages
 * @var Language $language
 */

use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\common\entities\ProductFile;
use xalberteinsteinx\shop\common\entities\ProductFileTranslation;
use bl\multilang\entities\Language;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<!--Tabs-->
<?= $this->render('../product/_product-tabs', [
    'product' => $product,
    'selectedLanguage' => $language
]); ?>

<div class="box padding20">

    <?php Pjax::begin(); ?>

    <?php $form = ActiveForm::begin([
        'action' => [
            'add-file',
            'id' => $product->id,
            'languageId' => $language->id
        ],
        'method' => 'post',
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'price',
            'data-pjax' => true
        ]
    ]) ?>

    <h2><?= \Yii::t('shop', 'Files'); ?></h2>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="col-md-3 text-center"><?= \Yii::t('shop', 'File'); ?></th>
            <th class="col-md-3 text-center"><?= \Yii::t('shop', 'Type'); ?></th>
            <th class="col-md-2 text-center"><?= \Yii::t('shop', 'Description'); ?></th>
            <th class="col-md-2 text-center"><?= \Yii::t('shop', 'Control'); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <!--FILE-->
            <td>
                <?= $form->field($fileModel, 'file')->fileInput()->label(false); ?>
            </td>
            <!--TYPE-->
            <td>
                <?= $form->field($fileTranslationModel, 'type')->label(false) ?>
            </td>
            <!--DESCRIPTION-->
            <td>
                <?= $form->field($fileTranslationModel, 'description')->label(false) ?>
            </td>
            <td>
                <?= Html::submitButton(\Yii::t('shop', 'Add'), ['class' => 'btn btn-primary']) ?>
            </td>
        </tr>
        <?php if (!empty($fileList)): ?>
            <?php foreach ($fileList as $file): ?>
                <tr class="text-center">

                    <!--FILE-->
                    <td>
                        <div class="image-link">
                            <p>
                                <?php $fileLink = str_replace(Yii::$app->homeUrl, '', Url::home(true)) . '/files/' . $file->file; ?>
                                <a href="<?= Url::to($fileLink); ?>">
                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                </a>
                                <?= $fileLink; ?>
                            </p>
                        </div>
                    </td>

                    <!--TYPE-->
                    <td>
                        <?php if (!empty($file->translation)): ?>
                            <?= (!empty($file->getTranslation($language->id))) ?
                                $file->getTranslation($language->id)->type : '' ?>
                        <?php endif; ?>
                    </td>

                    <!--DESCRIPTION-->
                    <td>
                        <?php if (!empty($file->translation)): ?>
                            <?= (!empty($file->getTranslation($language->id))) ?
                                $file->getTranslation($language->id)->description : '' ?>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <?= Html::a('', [
                            'update-file',
                            'productId' => $product->id,
                            'fileId' => $file->id,
                            'languageId' => $language->id
                        ],
                            [
                                'class' => 'pjax glyphicon glyphicon-edit text-warning btn btn-default btn-sm'
                            ]
                        ) ?>
                        <?= Html::a('', [
                            'remove-file',
                            'fileId' => $file->id,
                            'productId' => $product->id,
                            'languageId' => $language->id
                        ],
                            [
                                'class' => 'pjax glyphicon glyphicon-remove text-danger btn btn-default btn-sm'
                            ]
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <?php $form->end() ?>
    <?php Pjax::end(); ?>

</div>