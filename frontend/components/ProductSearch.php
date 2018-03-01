<?php

namespace sointula\shop\frontend\components;

use sointula\shop\common\entities\Category;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use sointula\shop\common\entities\Product;
use yii\helpers\ArrayHelper;

/**
 * ProductSearch represents the model behind the search form about `sointula\shop\common\entities\Product`.
 */
class ProductSearch extends Product
{

    /**
     * Sorting methods.
     */
    CONST SORT_CHEAP = 'cheap';
    CONST SORT_EXPENSIVE = 'expensive';
    CONST SORT_NEW = 'new';
    CONST SORT_OLD = 'old';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'vendor_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @param $descendantCategories Category[]
     * @param null|integer $vendor_id
     * @param null|integer $availability_id
     * @return ActiveDataProvider
     * @throws Exception if search is not validated
     */
    public function search($params, $descendantCategories, $vendor_id = null, $availability_id = null)
    {

        $this->load($params, '');
        $query = Product::find()->joinWith('category');

        if (\Yii::$app->controller->module->showChildCategoriesProducts) {

            if (!empty($params['id'])) {
                $query->where(['in', 'category_id', ArrayHelper::map($descendantCategories, 'id', 'id')]);
            }

        }
        else {
            if (!empty($params['id'])) {
                $query->where(['category_id' => $params['id']]);
            }
        }

        /*Find by vendor*/
        if (!empty($vendor_id)) {
            $query->joinWith('vendor')->where(['vendor_id' => $vendor_id]);
        }
        /*Find by availability*/
        if (!empty($availability_id)) {
            $query->joinWith('productAvailability')->where(['availability' => $availability_id]);
        }

        $query->andWhere(['status' => Product::STATUS_SUCCESS, 'shop_product.show' => true, 'additional_products' => false]);

        switch (ArrayHelper::getValue($params, 'sort')) {
            case self::SORT_CHEAP:
                $query->joinWith('defaultCombination.combinationPrices.price p');
                $query->joinWith('productPrices.price u');

                $query->orderBy(['(p.price - p.discount)' => SORT_ASC, '(u.price - u.discount)' => SORT_ASC]);
                break;

            case self::SORT_EXPENSIVE:
                $query->joinWith('defaultCombination.combinationPrices.price p');
                $query->joinWith('productPrices.price u');

                $query->orderBy(['(p.price - p.discount)' => SORT_DESC, '(u.price - u.discount)' => SORT_DESC]);
                break;

            case self::SORT_OLD:
                $query->orderBy(['creation_time' => SORT_ASC]);
                break;

            case self::SORT_NEW:
                $query->orderBy(['creation_time' => SORT_DESC]);
                break;

            default:
                $query->orderBy(['category_id' => SORT_ASC, 'position' => SORT_ASC]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if ($this->validate()) {
            return $dataProvider;
        }
        else throw new Exception();
    }
}