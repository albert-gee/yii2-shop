<?php
namespace xalberteinsteinx\shop\common\entities;

use xalberteinsteinx\shop\common\components\CartComponent;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use xalberteinsteinx\shop\common\entities\OrderProduct;

/**
 * SearchOrderProduct represents the model behind the search form about `xalberteinsteinx\shop\common\entities\OrderProduct`.
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class SearchOrderProduct extends OrderProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'order_id', 'count'], 'integer'],
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
     * @param string $status
     *
     * @return ActiveDataProvider
     */
    public function search($params, $status = null)
    {
        $order = (empty($status)) ? Order::find()->where(['user_id' => \Yii::$app->user->id])->one() :
            Order::find()->where(['user_id' => \Yii::$app->user->id, 'status' => $status])->one();

        if (!empty($order)) {
            $query = OrderProduct::find();

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
                'order_id' => $order->id,
                'count' => $this->count,
            ]);

            return $dataProvider;
        }


    }
}