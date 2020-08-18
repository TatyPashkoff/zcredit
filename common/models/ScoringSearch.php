<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Scoring;

/**
 * ScoringSearch represents the model behind the search form about `common\models\Scoring`.
 */
class ScoringSearch extends Scoring
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'cards_add_id', 'created_at', 'updated_at', 'date_start', 'date_end'], 'integer'],
            [['pan', 'exp', 'phone', 'fullname', 'balance', 'summ', 'data', 'sms'], 'safe'],
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
        $query = Scoring::find();

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
            'cards_add_id' => $this->cards_add_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
        ]);

        $query->andFilterWhere(['like', 'pan', $this->pan])
            ->andFilterWhere(['like', 'exp', $this->exp])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'balance', $this->balance])
            ->andFilterWhere(['like', 'summ', $this->summ])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'sms', $this->sms]);

        return $dataProvider;
    }
}
