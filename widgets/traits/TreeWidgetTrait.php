<?php
namespace albertgeeca\shop\widgets\traits;

use bl\multilang\entities\Language;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use albertgeeca\shop\common\entities\Category;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
trait TreeWidgetTrait
{
    /**
     * @param null|integer $parentId
     * @param integer $level
     * @param integer $currentCategoryId
     * @param boolean $isGrid
     * @param string $downIconClass
     * @param string $upIconClass
     * @param integer $languageId
     * @return mixed
     * @throws BadRequestHttpException
     * @throws Exception
     *
     * This action is used by Tree wiget
     */
    public function actionGetCategories($parentId = null, $level,
                                        $currentCategoryId = null, $isGrid = false,
                                        $downIconClass, $upIconClass, $languageId = null)
    {

        if (\Yii::$app->request->isAjax) {
            if (!empty($level)) {
                $currentCategory = (!empty($this->currentCategoryId)) ? Category::findOne($this->currentCategoryId) : NULL;
                $categories = Category::find()->where(['parent_id' => $parentId])->orderBy('position')->all();

                $params = [
                    'categories' => $categories,
                    'level' => $level,
                    'currentCategoryId' => $currentCategoryId,
                    'currentCategoryParentId' => (!empty($currentCategory)) ? $currentCategory->parent_id : '',
                    'languageId' => $languageId ?? Language::getCurrent()->id,
                    'downIconClass' => $downIconClass,
                    'upIconClass' => $upIconClass
                ];
                /**
                 * @var $this \yii\web\Controller
                 */
                if ($isGrid == 'true') {
                    return $this->renderAjax(
                        '@vendor/albert-sointula/yii2-shop/widgets/views/tree/grid-tr', $params);
                }
                else {
                    return $this->renderAjax(
                        '@vendor/albert-sointula/yii2-shop/widgets/views/tree/categories-ajax', $params);
                }
            } else throw new Exception();
        } else throw new BadRequestHttpException();
    }
}