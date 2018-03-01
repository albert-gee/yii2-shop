<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $product \sointula\shop\common\entities\Product
 * @var $language \bl\multilang\entities\Language
 * @var $fileModel \sointula\shop\backend\components\form\ProductFileForm
 * @var $fileTranslationModel \sointula\shop\common\entities\ProductFileTranslation
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'method' => 'post',
    'options' => [
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
                <?= Html::a(
                    $fileTranslationModel->productFile->file,
                    Url::to('/files/' . $fileTranslationModel->productFile->file),
                    ['target' => '_blank']); ?>
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
                <?= Html::submitButton(\Yii::t('shop', 'Save'), ['class' => 'pjax btn btn-primary', 'style' => 'width: 100%;']) ?>
            </td>
        </tr>

        </tbody>
    </table>
<?php $form->end() ?>