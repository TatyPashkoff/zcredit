<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Polises;

/**
 * PolisesSearch represents the model behind the search form about `common\models\Polises`.
 */
class PolisesSearch extends Polises
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'credit_id', 'contract_id', 'client_id', 'supplier_id', 'created_at', 'contractRegistrationID'], 'integer'],
            [['polisSeries', 'polisNumber', 'status'], 'safe'],
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
        $query = Polises::find();

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
            'credit_id' => $this->credit_id,
            'contract_id' => $this->contract_id,
            'client_id' => $this->client_id,
            'supplier_id' => $this->supplier_id,
            'created_at' => $this->created_at,
            'contractRegistrationID' => $this->contractRegistrationID,
        ]);

        $query->andFilterWhere(['like', 'polisSeries', $this->polisSeries])
            ->andFilterWhere(['like', 'polisNumber', $this->polisNumber])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
