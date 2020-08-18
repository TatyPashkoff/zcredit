<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Kyc;

/**
 * KycSearch represents the model behind the search form about `common\models\Kyc`.
 */
class KycSearch extends Kyc
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'client_id', 'date_verify'], 'integer'],
            [['status_verify', 'status', 'delay', 'credit_rating'], 'safe'],
            [['salary', 'credit_month', 'credit_year'], 'number'],
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
        $query = Kyc::find()->with('client');

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
            'created_at' => $this->created_at,
            'client_id' => $this->client_id,
            'date_verify' => $this->date_verify,
            'salary' => $this->salary,
            'credit_month' => $this->credit_month,
            'credit_year' => $this->credit_year,
        ]);

        $query->andFilterWhere(['like', 'status_verify', $this->status_verify])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'delay', $this->delay])
            ->andFilterWhere(['like', 'credit_rating', $this->credit_rating]);

        return $dataProvider;
    }
}
