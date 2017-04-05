<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 * This view is used for rendering in TreeWidget if it is grid.
 *
 * @var $categories xalberteinsteinx\shop\common\entities\Category
 * @var $currentCategoryId integer
 * @var $level integer
 * @var $upIconClass string
 * @var $downIconClass string
 * @var $languageId string
 */

use xalberteinsteinx\shop\widgets\ManageButtons;
use xalberteinsteinx\shop\widgets\TreeWidget;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>
<?php if (!empty($categories)) : ?>
    <ul data-level="<?= $level + 1; ?>">
        <?php foreach ($categories as $category) : ?>
            <li class="<?= ($category->id == $currentCategoryId) ? 'current' : ''; ?>">
                <table>
                    <tr>
                        <td class="col-md-1">
                            <?php if (!empty($category->allChildren)) : ?>
                                <span class="<?= $downIconClass; ?> pull-right category-toggle"
                                      id="<?= $category->id; ?>" data-opened="<?= (!empty($category->id)) ?
                                    TreeWidget::isOpened($category->id, $currentCategoryId) :
                                    ''; ?>">
                                </span>
                            <?php endif; ?>
                        </td>
                        <!--TITLE-->
                        <td class="<?= ($category->id == $currentCategoryId) ? 'current' : ''; ?> project-title col-md-3">
                            <a href="<?= Url::toRoute(['/shop/category/save', 'id' => $category->id, 'languageId' => $languageId]); ?>">
                                <?php if (!$level == 1) : ?>
                                    <span>
                                <?= (!empty($category->translation)) ? $category->translation->title : ''; ?>
                            </span>
                                <?php else : ?>
                                    <span>
                                <?= (!empty($category->translation)) ? $category->translation->title : ''; ?>
                            </span>
                                <?php endif; ?>
                            </a>
                        </td>

                        <!--PARENT-->
                        <td class="col-md-2">
                            <?= (!empty($category->parent)) ? $category->parent->translation->title : ''; ?>
                        </td>

                        <!--IMAGES-->
                        <td class="project-people col-md-2">
                            <?php
                            $content = '';
                            if (!empty($category->cover)) {
                                $content .= Html::img($category->getImage('shop-category/cover', 'small'), ['class' => 'img-circle']);
                            }
                            if (!empty($category->thumbnail)) {
                                $content .= Html::img($category->getImage('shop-category/thumbnail', 'small'), ['class' => 'img-circle']);
                            }
                            if (!empty($category->menu_item)) {
                                $content .= Html::img($category->getImage('shop-category/menu_item', 'small'), ['class' => 'img-circle']);
                            }
                            echo Html::a($content, Url::toRoute(['add-images', 'categoryId' => $category->id, 'languageId' => $languageId]));
                            ?>
                        </td>
                        <!--SHOW-->
                        <td class="col-md-1 text-center">
                            <?= Html::a(
                                Html::tag('i', '', ['class' => $category->show ? 'glyphicon glyphicon-ok text-primary' : 'glyphicon glyphicon-minus text-danger']),
                                Url::to([
                                    'switch-show',
                                    'id' => $category->id
                                ]),
                                [
                                    'class' => 'category-nav show-category'
                                ]);
                            ?>
                        </td>
                        <!--POSITION-->
                        <td class="col-md-1 text-center">
                            <?php
                            $buttonUp = Html::a('', Url::toRoute(['up', 'id' => $category->id]),
                                ['class' => 'fa fa-chevron-up change-category-position']);
                            $buttonDown = Html::a('', Url::toRoute(['down', 'id' => $category->id]),
                                ['class' => 'fa fa-chevron-down change-category-position']);
                            echo $buttonUp . '<div class="position-index">' . $category->position . '</div>' . $buttonDown; ?>
                        </td>
                        <!--CONTROL-->
                        <td class="col-md-2">
                            <?= ManageButtons::widget(['model' => $category]); ?>
                        </td>
                    </tr>
                </table>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>