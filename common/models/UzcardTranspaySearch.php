<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UzcardTranspay;

/**
 * UzcardTranspaySearch represents the model behind the search form about `common\models\UzcardTranspay`.
 */
class UzcardTranspaySearch extends UzcardTranspay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'payment_id', 'created_at'], 'integer'],
            [['trans_id', 'refNum', 'ext', 'pan', 'exp', 'tranType', 'date12', 'field38', 'respSV', 'respText', 'status'], 'safe'],
            [['amount'], 'number'],
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
        $query = UzcardTranspay::find();

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
            'payment_id' => $this->payment_id,
            'created_at' => $this->created_at,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'trans_id', $this->trans_id])
            ->andFilterWhere(['like', 'refNum', $this->refNum])
            ->andFilterWhere(['like', 'ext', $this->ext])
            ->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'exp', $this->exp])
            ->andFilterWhere(['like', 'tranType', $this->tranType])
            ->andFilterWhere(['like', 'date12', $this->date12])
            ->andFilterWhere(['like', 'field38', $this->field38])
            ->andFilterWhere(['like', 'respSV', $this->respSV])
            ->andFilterWhere(['like', 'respText', $this->respText])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
