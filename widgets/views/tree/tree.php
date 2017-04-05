<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * This view is used for by TreeWidget
 *
 * @var $categories bl\cms\shop\common\entities\Category
 * @var $currentCategoryId integer
 * @var $currentCategoryParentId integer
 * @var $level integer
 * @var $upIconClass string
 * @var $downIconClass string
 * @var $languageId string
 * @var $isGrid boolean
 * @var $appName string
 */

$params = [
    'categories' => $categories,
    'currentCategoryId' => $currentCategoryId,
    'currentCategoryParentId' => $currentCategoryParentId,
    'level' => $level,
    'upIconClass' => $upIconClass,
    'downIconClass' => $downIconClass,
    'languageId' => $languageId,
    'appName' => $appName,
];
?>

<div id="widget-menu" data-current-category-id="<?= $currentCategoryId; ?>" data-language="<?= $languageId; ?>"
     data-is-grid=<?= $isGrid ? 'true' : 'false'; ?>>
    <?php if ($isGrid) : ?>
        <table id="my-grid" class="table table-hover">
            <thead>
            <tr>
                <th class="text-center col-md-1"></th>
                <th class="text-center col-md-3"><?= \Yii::t('shop', 'Title'); ?></th>
                <th class="text-center col-md-2"><?= \Yii::t('shop', 'Parent category'); ?></th>
                <th class="text-center col-md-2"><?= \Yii::t('shop', 'Images'); ?></th>
                <th class="text-center col-md-1"><?= \Yii::t('shop', 'Show'); ?></th>
                <th class="text-center col-md-1"><?= \Yii::t('shop', 'Position'); ?></th>
                <th class="text-center col-md-2"><?= \Yii::t('shop', 'Control'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr></tr>
            </tbody>
        </table>
        <?= $this->render(
            '@vendor/xalberteinsteinx/yii2-shop/widgets/views/tree/grid-tr',
            $params
        );
        ?>

    <?php else : ?>
        <?= $this->render(
            '@vendor/xalberteinsteinx/yii2-shop/widgets/views/tree/categories-ajax',
            $params
        );
        ?>
    <?php endif; ?>
</div>

<?php
$this->registerJs('
    var upIconClass = "' . $upIconClass . '";
    var downIconClass = "' . $downIconClass . '";
    var appName = "' . $appName . '";
', $this::POS_HEAD);
?>

