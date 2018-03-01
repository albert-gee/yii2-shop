<?php
namespace sointula\shop;

use sointula\shop\common\entities\Category;
use sointula\shop\common\entities\CategoryTranslation;
use sointula\shop\common\entities\Product;
use sointula\shop\common\entities\ProductTranslation;
use bl\multilang\entities\Language;
use bl\seo\entities\SeoData;
use Yii;
use yii\base\Object;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\UrlManager;
use yii\web\UrlRuleInterface;

/**
 * @author Gutsulyak Vadim <guts.vadim@gmail.com>
 */
class UrlRule extends Object implements UrlRuleInterface
{
    public $prefix = '';
    private $pathInfo;
    private $routes;
    private $routesCount;
    private $currentLanguage;
    private $productRoute = 'shop/product/show';
    private $categoryRoute = 'shop/category/show';

    /**
     * @var Language
     */
    private $_currentManagerLanguage;

    /**
     * Parses the given request and returns the corresponding route and parameters.
     * @param UrlManager $manager the URL manager
     * @param Request $request the request component
     * @return array|bool the parsing result. The route and the parameters are returned as an array.
     * If false, it means this rule cannot be used to parse this path info.
     * @throws NotFoundHttpException
     */
    public function parseRequest($manager, $request) {
        $this->currentLanguage = Language::getCurrent();
        $this->pathInfo = $request->getPathInfo();

        if($this->pathInfo == $this->categoryRoute || $this->pathInfo == $this->productRoute) {
            Yii::$app->urlManager->language = $this->currentLanguage;
            if($this->createUrl(Yii::$app->urlManager, $this->pathInfo, $request->getQueryParams())) {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }
            else if($this->pathInfo == $this->productRoute && empty($request->getQueryParams()['id'])) {
                throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }
        }

        if(!empty($this->prefix)) {
            if(strpos($this->pathInfo, $this->prefix) === 0) {
                $this->pathInfo = substr($this->pathInfo, strlen($this->prefix));
            }
            else {
                return false;
            }
        }
        $this->initRoutes($this->pathInfo);
        if(!empty($this->prefix) && $this->routesCount == 1) {
            return [$this->categoryRoute, []];
        }
        $categoryId = null;
        for($i = 1; $i < $this->routesCount; $i++) {
            if($i === $this->routesCount - 1) {
                if($product = $this->findProductBySeoUrl($this->routes[$i], $categoryId)) {
                    return [
                        '/' . $this->productRoute,
                        ['id' => $product->id]
                    ];
                }
                else {
                    if($category = $this->findCategoryBySeoUrl($this->routes[$i], $categoryId)) {
                        return [
                            '/' . $this->categoryRoute,
                            ['id' => $category->id]
                        ];
                    }
                    else {
                        return false;
                    }
                }
            }
            else {
                if($category = $this->findCategoryBySeoUrl($this->routes[$i], $categoryId)) {
                    $categoryId = $category->id;
                }
                else {
                    return false;
                }
            }
        }
        return false;
    }
    private function initRoutes($pathInfo) {
        $this->routes = explode('/', $pathInfo);
        $this->routesCount = count($this->routes);
    }
    private function findProductBySeoUrl($seoUrl, $categoryId, $options = []) {
        $productsSeoData = SeoData::find()
            ->where([
                'entity_name' => ProductTranslation::className(),
                'seo_url' => $seoUrl
            ])->all();
        if($productsSeoData) {
            foreach($productsSeoData as $productSeoData) {
                if($product = Product::find()
                    ->joinWith('translations translation')
                    ->where(array_merge([
                        'translation.id' => $productSeoData->entity_id,
                        'category_id' => $categoryId,
                        'translation.language_id' => $this->currentLanguage->id
                    ], $options))->one()) {
                    return $product;
                }
            }
        }
        return null;
    }
    private function findCategoryBySeoUrl($seoUrl, $parentId, $options = []) {
        $categoriesSeoData = SeoData::find()
            ->where([
                'entity_name' => CategoryTranslation::className(),
                'seo_url' => $seoUrl
            ])->all();
        if($categoriesSeoData) {
            foreach($categoriesSeoData as $categorySeoData) {
                if($category = Category::find()
                    ->joinWith('translations translation')
                    ->where(array_merge([
                        'translation.id' => $categorySeoData->entity_id,
                        'parent_id' => $parentId,
                        'translation.language_id' => $this->currentLanguage->id
                    ], $options))->one()) {
                    return $category;
                }
            }
        }
        return null;
    }
    /**
     * Creates a URL according to the given route and parameters.
     * @param UrlManager $manager the URL manager
     * @param string $route the route. It should not have slashes at the beginning or the end.
     * @param array $params the parameters
     * @return string|boolean the created URL, or false if this rule cannot be used for creating this URL.
     */
    public function createUrl($manager, $route, $params)
    {
        $pathInfo = '';
        if($route == $this->categoryRoute && empty($params['id'])) {
            $pathInfo = $this->prefix;
        }
        else if(($route == $this->productRoute || $route == $this->categoryRoute) && !empty($params['id'])) {
            $id = $params['id'];
            $parentId = null;
            $this->_currentManagerLanguage = Language::findOne(['lang_id' => $manager->language]);

            if($route == $this->productRoute) {
                /** @var Product $product */
                $product = Product::find()
                    ->with([
                        'translation' => function (\yii\db\ActiveQuery $query) {
                            $query->onCondition(['language_id' => $this->_currentManagerLanguage->id]);
                        },
                    ])
                    ->where(['id' => $id])
                    ->one();

                if(empty($product)) {
                    return false;
                }

                if(!empty($product->translation->seoUrl)) {
                    $pathInfo = $product->translation->seoUrl;
                    $parentId = $product->category_id;
                }
                else {
                    return false;
                }
            }
            else if($route == $this->categoryRoute) {
                /** @var Category $category */
                $category = Category::find()
                    ->with([
                        'translation' => function (\yii\db\ActiveQuery $query) {
                            $query->onCondition(['language_id' => $this->_currentManagerLanguage->id]);
                        },
                    ])
                    ->where(['id' => $id])
                    ->one();

                if(empty($category)) {
                    return false;
                }

                if(!empty($category->translation->seoUrl)) {
                    $pathInfo = $category->translation->seoUrl;
                    $parentId = $category->parent_id;
                }
                else {
                    return false;
                }
            }
            while($parentId != null) {
                /** @var Category $category */
                $category = Category::find()
                    ->with([
                        'translation' => function (\yii\db\ActiveQuery $query) {
                            $query->onCondition(['language_id' => $this->_currentManagerLanguage->id]);
                        },
                    ])
                    ->where(['id' => $parentId])
                    ->one();

                if(empty($category)) {
                    return false;
                }

                if(!empty($category->translation->seoUrl)) {
                    $pathInfo = $category->translation->seoUrl . '/' . $pathInfo;
                    $parentId = $category->parent_id;
                }
                else {
                    return false;
                }
            }
            if(!empty($this->prefix)) {
                $pathInfo = $this->prefix . '/' . $pathInfo;
            }
            unset($params['id']);
        }
        else {
            return false;
        }

        $queryParams = (!empty(http_build_query($params))) ? '?' . http_build_query($params) : '';
        return $pathInfo . $queryParams;
    }
}