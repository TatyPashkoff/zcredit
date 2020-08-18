<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingHistory;

/**
 * BillingHistorySearch represents the model behind the search form about `common\models\BillingHistory`.
 */
class BillingHistorySearch extends BillingHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'created_at', 'status'], 'integer'],
            [['summ'], 'number'],
            [['payment_type', 'state'], 'safe'],
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
        $query = BillingHistory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]]
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
            'summ' => $this->summ,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'payment_type', $this->payment_type]);
		$query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
