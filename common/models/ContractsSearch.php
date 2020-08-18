<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Contracts;

/**
 * ContractsSearch represents the model behind the search form about `common\models\Contracts`.
 */
class ContractsSearch extends Contracts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['credit_id', 'created_at','supplier_id', 'date_start', 'date_end', 'status'], 'safe'],
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
        $query = Contracts::find();

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
        ]);

        $query->andFilterWhere(['like', 'credit_id', $this->credit_id])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'supplier_id', $this->created_at])
            ->andFilterWhere(['like', 'date_start', $this->date_start])
            ->andFilterWhere(['like', 'date_end', $this->date_end])
            ->andFilterWhere(['like', 'prosrochka', $this->payment->price]);

        return $dataProvider;
    }
}
