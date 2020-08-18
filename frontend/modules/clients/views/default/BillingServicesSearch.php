<?php

namespace common\models;

use common\models\BillingHistory;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingServices;

/**
 * BillingHistorySearch represents the model behind the search form about `common\models\BillingServices`.
 */
class BillingServicesSearch extends BillingServices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','user_id', 'created_at','amount','service_type','status'], 'integer'],
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
        $query = BillingServices::find();

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
            'created_at' => $this->created_at,
            'amount' => $this->amount,
            'service_type' => $this->service_type,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
