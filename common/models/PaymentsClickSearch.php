<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymentsClick;

/**
 * PaymentsClickSearch represents the model behind the search form about `common\models\PaymentsClick`.
 */
class PaymentsClickSearch extends PaymentsClick
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'user_id', 'invoice_id', 'payment_id'], 'integer'],
            [['status', 'status_note', 'created', 'modified', 'currency', 'description', 'card_token', 'token', 'phone_number', 'merchant_trans_id', 'note'], 'safe'],
            [['total', 'amount', 'delivery', 'tax'], 'number'],
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
        $query = PaymentsClick::find();

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
            'product_id' => $this->product_id,
            'created' => $this->created,
            'modified' => $this->modified,
            'total' => $this->total,
            'amount' => $this->amount,
            'delivery' => $this->delivery,
            'tax' => $this->tax,
            'user_id' => $this->user_id,
            'invoice_id' => $this->invoice_id,
            'payment_id' => $this->payment_id,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'status_note', $this->status_note])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'card_token', $this->card_token])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'merchant_trans_id', $this->merchant_trans_id])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
