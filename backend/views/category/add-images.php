<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $this yii\web\View
 * @var $languages [] bl\multilang\entities\Language
 * @var $selectedLanguage bl\multilang\entities\Language
 * @var $category \xalberteinsteinx\shop\common\entities\Category
 * @var $image_form xalberteinsteinx\shop\backend\components\form\CategoryImageForm
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php $addForm = ActiveForm::begin([
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
        'data-pjax' => true,
    ]
]); ?>

    <h2><?= \Yii::t('shop', 'Images'); ?></h2>
    <table class="table-bordered table-condensed">
        <thead class="thead-inverse">
        <tr>
            <th class="text-center col-md-1">
                <?= \Yii::t('shop', 'Type'); ?>
            </th>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <th class="text-center col-md-2">
                    <?= \Yii::t('shop', 'Image preview'); ?>
                </th>
                <th class="text-center col-md-5">
                    <?= \Yii::t('shop', 'Image URL'); ?>
                </th>
            <?php endif; ?>
            <th class="text-center col-md-3">
                <?= \Yii::t('shop', 'Upload from disk'); ?>
            </th>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <th class="text-center col-md-1">
                    <?= \Yii::t('shop', 'Delete'); ?>
                </th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <!--MENU ITEM-->
        <tr>
            <td class="text-center">
                <?= \Yii::t('shop', 'Menu item'); ?>
            </td>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <td>
                    <?php if (!empty($category->menu_item)) : ?>
                        <img data-toggle="modal" data-target="#menuItemModal"
                             src="<?= $category->getImage('shop-category/menu_item', 'small'); ?>"
                             class="thumb">
                        <!-- Modal -->
                        <div id="menuItemModal" class="modal fade" role="dialog">
                            <img style="display: block" class="modal-dialog"
                                 src="<?= $category->getImage('shop-category/menu_item', 'thumb'); ?>">
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($category->menu_item)) : ?>
                        <div class="image-link">
                            <p>
                                <?php $link = str_replace(Yii::$app->homeUrl, '', Url::home(true)) . $category->getImage('shop-category/menu_item', 'big'); ?>
                                <a href="<?= Url::to($link); ?>" target="_blank">
                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                </a>
                                <?= $link; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <td>
                <?= $addForm->field($image_form, 'menu_item')->fileInput()->label(\Yii::t('shop', 'Upload image')); ?>
            </td>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <td class="text-center">
                    <?php if (!empty($category->menu_item)) : ?>
                        <a href="<?= Url::toRoute(['delete-image', 'id' => $category->id, 'imageType' => 'menu_item']); ?>"
                           class="glyphicon glyphicon-remove text-danger btn btn-default btn-sm"></a>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <!--THUMBNAIL-->
        <tr>
            <td class="text-center">
                <?= \Yii::t('shop', 'Thumbnail'); ?>
            </td>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <td>
                    <?php if (!empty($category->thumbnail)) : ?>
                    <img data-toggle="modal" data-target="#thumbnailModal"
                         src="<?= $category->getImage('shop-category/thumbnail', 'small'); ?>"
                         class="thumb">
                    <!-- Modal -->
                    <div id="thumbnailModal" class="modal fade" role="dialog">
                        <img style="display: block" class="modal-dialog"
                             src="<?= $category->getImage('shop-category/thumbnail', 'thumb'); ?>">
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <?php if (!empty($category->thumbnail)) : ?>
                        <div class="image-link">
                            <p>
                                <?php $link = str_replace(Yii::$app->homeUrl, '', Url::home(true)) .
                                    $category->getImage('shop-category/thumbnail', 'big'); ?>
                                <a href="<?= Url::to($link); ?>" target="_blank">
                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                </a>
                                <?= $link; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <td>
                <?= $addForm->field($image_form, 'thumbnail')->fileInput()->label(\Yii::t('shop', 'Upload image')); ?>
            </td>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <td class="text-center">
                    <?php if (!empty($category->thumbnail)) : ?>
                        <a href="<?= Url::toRoute(['delete-image', 'id' => $category->id, 'imageType' => 'thumbnail']); ?>"
                           class="glyphicon glyphicon-remove text-danger btn btn-default btn-sm"></a>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <!--COVER-->
        <tr>
            <td class="text-center">
                <?= \Yii::t('shop', 'Cover'); ?>
            </td>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <td>
                    <?php if (!empty($category->cover)) : ?>
                        <img data-toggle="modal" data-target="#coverModal"
                             src="<?= $category->getImage('shop-category/cover', 'small'); ?>"
                             class="thumb">
                        <!-- Modal -->
                        <div id="coverModal" class="modal fade" role="dialog">
                            <img style="display: block" class="modal-dialog"
                                 src="<?= $category->getImage('shop-category/cover', 'thumb'); ?>">
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($category->cover)) : ?>
                        <div class="image-link">
                            <p>
                                <?php $link = str_replace(Yii::$app->homeUrl, '', Url::home(true)) .
                                    $category->getImage('shop-category/cover', 'big'); ?>
                                <a href="<?= Url::to($link); ?>" target="_blank">
                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                </a>
                                <?= $link; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
            <td>
                <?= $addForm->field($image_form, 'cover')->fileInput()->label(\Yii::t('shop', 'Upload image')); ?>
            </td>
            <?php if (!empty($category->menu_item) || !empty($category->thumbnail) || !empty($category->cover)) : ?>
                <td class="text-center">
                    <?php if (!empty($category->cover)) : ?>
                        <a href="<?= Url::toRoute(['delete-image', 'id' => $category->id, 'imageType' => 'cover']); ?>"
                           class="glyphicon glyphicon-remove text-danger btn btn-default btn-sm"></a>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        </tbody>
    </table>

    <div class="box">
        <!--CANCEL BUTTON-->
        <a href="<?= Url::to(['/shop/category']); ?>">
            <?= Html::button(\Yii::t('shop', 'Cancel'), [
                'class' => 'btn btn-danger btn-xs pull-right'
            ]); ?>
        </a>
        <?= Html::submitButton(Yii::t('shop', 'Upload'), ['class' => 'btn btn-xs btn-primary m-r-xs pull-right']) ?>
    </div>
<?php $addForm::end(); ?>