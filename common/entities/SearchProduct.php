<?php

namespace sointula\shop\common\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use sointula\shop\common\entities\Product;

/**
 * ProductSearch represents the model behind the search form about `sointula\shop\common\entities\Product`.
 */
class SearchProduct extends Product
{

    public $title;
    public $category;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'category', 'status'], 'safe']
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = (\Yii::$app->user->can('viewCompleteProductList')) ?
            Product::find()->joinWith('translations') :
            Product::find()->joinWith('translations')->where(['owner' => \Yii::$app->user->id]);

        $this->load($params);

        if (empty($params['sort'])) {
            $query->orderBy(['category_id' => SORT_ASC, 'position' => SORT_ASC]);
        }

        $query->andFilterWhere([
            'shop_product.category_id' => $this->category,
        ])->andFilterWhere(['like', 'shop_product_translation.title', $this->title
        ])->andFilterWhere(['shop_product.status' => $this->status]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['shows']],

            'pagination' => [
                'pagesize' => 10,
            ],
        ]);

        if (!$this->validate()) {

            return $dataProvider;
        }

        return $dataProvider;
    }
}