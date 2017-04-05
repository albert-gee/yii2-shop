<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * This view is used for ajax rendering in TreeWidget
 *
 * @var $categories bl\cms\shop\common\entities\Category
 * @var $currentCategoryId integer
 * @var $currentCategoryParentId integer
 * @var $level integer
 * @var $upIconClass string
 * @var $downIconClass string
 */
use bl\cms\shop\widgets\TreeWidget;
use yii\helpers\Url;
?>

<?php if (!empty($categories)) : ?>
    <ul data-level="<?= $level + 1; ?>">
        <?php foreach ($categories as $category) : ?>
            <li class="<?= ($category->id == $currentCategoryId) ? 'current' : '';?>">
                <a href="<?= Url::toRoute(['/shop/category/show', 'id' => $category->id]); ?>">
                    <?php if (!$level == 1) : ?>
                        <span>
                            <?= $category->translation->title; ?>
                        </span>
                    <?php else : ?>
                        <span style="margin-left:<?= $level * 15; ?>px">
                            <?= $category->translation->title; ?>
                        </span>
                    <?php endif; ?>
                </a>
                <?php if (!empty($category->children)) : ?>

                    <span class="<?= ($category->id == $currentCategoryParentId) ? $upIconClass : $downIconClass;?> pull-right category-toggle"
                       id="<?= $category->id; ?>" data-opened="<?= (!empty($category->id)) ?
                        TreeWidget::isOpened($category->id, $currentCategoryId) :
                        '';?>">
                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
