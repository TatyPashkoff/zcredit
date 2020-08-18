<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UzcardPayments;

/**
 * UzcardPaymentsSearch represents the model behind the search form about `common\models\UzcardPayments`.
 */
class UzcardPaymentsSearch extends UzcardPayments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'credit_item_id', 'payment_id', 'created_at'], 'integer'],
            [['username', 'refNum', 'ext', 'pan', 'pan2', 'expiry', 'tranType', 'date7', 'date12', 'amount', 'currency', 'stan', 'field38', 'field48', 'field91', 'merchantId', 'terminalId', 'resp', 'respText', 'respSV', 'status'], 'safe'],
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
        $query = UzcardPayments::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'credit_item_id' => $this->credit_item_id,
            'payment_id' => $this->payment_id,
            'Столбец 4' => $this->Столбец 4,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'refNum', $this->refNum])
            ->andFilterWhere(['like', 'ext', $this->ext])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'pan2', $this->pan2])
            ->andFilterWhere(['like', 'expiry', $this->expiry])
            ->andFilterWhere(['like', 'tranType', $this->tranType])
            ->andFilterWhere(['like', 'date7', $this->date7])
            ->andFilterWhere(['like', 'date12', $this->date12])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'stan', $this->stan])
            ->andFilterWhere(['like', 'field38', $this->field38])
            ->andFilterWhere(['like', 'field48', $this->field48])
            ->andFilterWhere(['like', 'field91', $this->field91])
            ->andFilterWhere(['like', 'merchantId', $this->merchantId])
            ->andFilterWhere(['like', 'terminalId', $this->terminalId])
            ->andFilterWhere(['like', 'resp', $this->resp])
            ->andFilterWhere(['like', 'respText', $this->respText])
            ->andFilterWhere(['like', 'respSV', $this->respSV])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
