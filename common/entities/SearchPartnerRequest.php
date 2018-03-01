<?php

namespace sointula\shop\common\entities;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use sointula\shop\common\entities\PartnerRequest;

/**
 * SearchPartnerRequest represents the model behind the search form about `sointula\shop\common\entities\PartnerRequest`.
 */
class SearchPartnerRequest extends PartnerRequest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sender_id', 'moderation_status', 'moderated_by'], 'integer'],
            [['company_name', 'website', 'message', 'created_at', 'moderated_at'], 'safe'],
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
        $query = PartnerRequest::find();

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
            'sender_id' => $this->sender_id,
            'created_at' => $this->created_at,
            'moderation_status' => $this->moderation_status,
            'moderated_by' => $this->moderated_by,
            'moderated_at' => $this->moderated_at,
        ]);

        $query->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'message', $this->message]);

        $query->orderBy(['id' => SORT_DESC]);

        return $dataProvider;
    }
}