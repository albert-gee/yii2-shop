<?php
namespace xalberteinsteinx\shop\widgets;

use xalberteinsteinx\shop\common\entities\Category;
use xalberteinsteinx\shop\common\entities\Product;
use xalberteinsteinx\shop\widgets\assets\TreeWidgetAsset;
use bl\multilang\entities\Language;
use Yii;
use yii\base\Widget;
use yii\web\NotFoundHttpException;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * This widget adds tree menu using shop categories.
 * On one page may be only one Tree widget.
 *
 * Example:
 * <?= TreeWidget::widget([
 *  'className' => Category::className(),
 *  'currentCategoryId' => $category->id
 * ]); ?>
 *
 * In your controller use xalberteinsteinx\shop\widgets\traits\TreeWidgetTrait;
 *
 */
class TreeWidget extends Widget
{
    public $className;

    public $currentCategoryId;

    /**
     * @var bool
     * If true - TreeWidgetAsset will registered.
     */
    public $enableAjax = true;

    /**
     * @var bool
     * If true - widget will be displayed as admin table.
     */
    public $isGrid = false;

    /**
     * @var string
     * If app is backend, $appName must be '/admin';
     */
    public $appName = '';

    /**
     * Sets css-class for span tag when category is closed
     * @var string
     */
    public $upIconClass = 'glyphicon glyphicon-minus';

    /**
     * Sets css-class for span tag when category is opened
     * @var string
     */
    public $downIconClass = 'glyphicon glyphicon-plus';

    public function init()
    {
        if ($this->enableAjax) {
            $asset = TreeWidgetAsset::register($this->getView());
            if ($this->isGrid) {
                $asset->css = [
                    'css/tree-grid.css'
                    ];
            }
        }
    }

    public function run()
    {
        parent::run();

        $currentLanguage = Language::getCurrent();
        $langId = $currentLanguage->id;

        if (!empty($this->className)) {
            $class = \Yii::createObject($this->className);
            $currentCategory = (!empty($this->currentCategoryId)) ? Category::findOne($this->currentCategoryId) : NULL;

            $categories = (!empty($this->appName)) ?
                $class::find()->where(['parent_id' => null])->orderBy('position')->all() :
                $class::find()->where(['parent_id' => null, 'show' => 1])->orderBy('position')->all();

            $currentCategoryId = '';

            if (Yii::$app->controller->module->id == 'shop') {
                if (Yii::$app->controller->id == 'category') {
                    $currentCategoryId = \Yii::$app->request->get('id');
                } elseif (Yii::$app->controller->id == 'product') {
                    $product = Product::findOne(\Yii::$app->request->get('id'));
                    $currentCategoryId = $product->category_id;
                }
            }

            $params = [
                'categories' => $categories,
                'currentCategoryId' => $currentCategoryId,
                'currentCategoryParentId' => (!empty($currentCategory)) ? $currentCategory->parent_id : NULL,
                'level' => 0,
                'context' => $this,
                'upIconClass' => $this->upIconClass,
                'downIconClass' => $this->downIconClass,
                'languageId' => $langId ?? '',
                'appName' => $this->appName,
                'isGrid' => $this->isGrid
            ];

            return $this->render('tree/tree', $params);
        } else return false;

    }

    public static function isOpened($categoryId, $currentCategoryId)
    {
        if (!empty($categoryId) && !empty($currentCategoryId)) {
            $parentCategoriesArray = self::findAllAncestry($currentCategoryId);

            if (in_array($categoryId, $parentCategoriesArray)) {
                return 'true';
            } else return 'false';
        } else return $categoryId;

    }

    /**
     * Gets all category's ancestry.
     * @param $categoryId
     * @param array $parentCategoriesArray
     * @return array
     * @throws NotFoundHttpException
     */
    private static function findAllAncestry($categoryId, $parentCategoriesArray = [])
    {
        $category = Category::findOne($categoryId);
        if (!empty($category)) {
            $parentCategoryId = $category->parent_id;
        }
        else throw new NotFoundHttpException();

        if (!empty($parentCategoryId)) {
            $parentCategoriesArray[] = $parentCategoryId;
            return self::findAllAncestry($parentCategoryId, $parentCategoriesArray);
        } else return $parentCategoriesArray;
    }

}