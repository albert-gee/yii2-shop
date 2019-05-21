<?php
namespace albertgeeca\shop\common\entities;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchFavoriteProduct represents the model behind the search form about `albertgeeca\shop\common\entities\FavoriteProduct`.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SearchFavoriteProduct extends FavoriteProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'user_id'], 'integer'],
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
        $query = FavoriteProduct::find()->where(['user_id' => \Yii::$app->user->id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id' => $this->product_id,
            'user_id' => $this->user_id,
        ]);

        return $dataProvider;
    }
}