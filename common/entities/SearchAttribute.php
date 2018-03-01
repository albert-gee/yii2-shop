<?php

namespace sointula\shop\common\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use sointula\shop\common\entities\ShopAttribute;

/**
 * SearchAttribute represents the model behind the search form about `sointula\shop\common\entities\ShopAttribute`.
 */
class SearchAttribute extends ShopAttribute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = ShopAttribute::find();

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
            'type_id' => $this->type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
