<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Insurance;

/**
 * InsuranceSearch represents the model behind the search form about `common\models\Insurance`.
 */
class InsuranceSearch extends Insurance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'contract_id', 'polis_id'], 'integer'],
            [['request_id', 'status'], 'safe'],
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
        $query = Insurance::find();

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
            'created_at' => $this->created_at,
            'contract_id' => $this->contract_id,
            'polis_id' => $this->polis_id,
        ]);

        $query->andFilterWhere(['like', 'request_id', $this->request_id])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
