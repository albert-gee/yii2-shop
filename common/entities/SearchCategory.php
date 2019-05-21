<?php

namespace albertgeeca\shop\common\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use albertgeeca\shop\common\entities\Category;

/**
 * SearchCategory represents the model behind the search form about `albertgeeca\shop\common\entities\Category`.
 * @author Albert Gainutdinov
 */

class SearchCategory extends Category
{

    public $title;
    public $parent_id;
    public $show;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'position', 'show'], 'integer'],
            [['cover', 'thumbnail', 'menu_item', 'title'], 'safe'],

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
        $query = Category::find()->joinWith('translations')->orderBy(['parent_id' => SORT_ASC, 'position' => SORT_ASC]);

        $this->load($params);

        // grid filtering conditions
        $query->andFilterWhere([
            'shop_category.parent_id' => $this->parent_id,
        ])->andFilterWhere(['like', 'shop_category_translation.title', $this->title
        ])->andFilterWhere(['shop_category.show' => $this->show]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}