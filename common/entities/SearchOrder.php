<?php

namespace sointula\shop\common\entities;

use sointula\shop\common\components\CartComponent;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use sointula\shop\common\entities\Order;

/**
 * SearchOrder represents the model behind the search form about `sointula\shop\common\entities\Order`.
 */
class SearchOrder extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'uid'], 'integer'],
//            [['first_name', 'last_name', 'email', 'phone', 'address', 'status'], 'safe'],
            [['status'], 'safe'],
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
        $query = Order::find()->where(['not in','status', [OrderStatus::STATUS_INCOMPLETE]]);

        $query->orderBy(['status' => SORT_ASC, 'id' => SORT_DESC]);

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
            'user_id' => $this->user_id,
        ]);

        $query
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'uid', $this->uid]);
        return $dataProvider;
    }
}