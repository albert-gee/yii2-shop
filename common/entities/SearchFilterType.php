<?php

namespace xalberteinsteinx\shop\common\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use xalberteinsteinx\shop\common\entities\FilterType;

/**
 * SearchFilterType represents the model behind the search form about `xalberteinsteinx\shop\common\entities\FilterType`.
 */
class SearchFilterType extends FilterType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['class_name', 'column', 'displaying_column', 'title'], 'safe'],
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
        $query = FilterType::find();

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
        ]);

        $query->andFilterWhere(['like', 'class_name', $this->class_name])
            ->andFilterWhere(['like', 'column', $this->column])
            ->andFilterWhere(['like', 'displaying_column', $this->displaying_column])
            ->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}