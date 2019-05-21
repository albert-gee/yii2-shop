<?php
namespace albertgeeca\shop\common\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use albertgeeca\shop\common\entities\ViewedProduct;

/**
 * SearchViewedProduct represents the model behind the search form about `albertgeeca\shop\common\entities\ViewedProduct`.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SearchViewedProduct extends ViewedProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'user_id'], 'integer'],
            [['creation_time', 'update_time'], 'safe'],
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
        $query = ViewedProduct::find()->where(['user_id' => \Yii::$app->user->id]);

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
            'creation_time' => $this->creation_time,
            'update_time' => $this->update_time,
        ]);

        return $dataProvider;
    }
}