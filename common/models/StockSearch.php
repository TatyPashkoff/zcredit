<?php

namespace common\models;

use common\models\Stock;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * StockSearch represents the model behind the search form about `common\models\Stock`.
 */
class StockSearch extends Stock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','date_start', 'date_end', 'percent', 'status','margin'], 'integer'],
            [['sum'], 'number'],
            [['title','company'],'string'],
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
        $query = Stock::find();

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
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'sum' => $this->sum,
            'percent' => $this->percent,
            'margin' => $this->margin,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
        ->andFilterWhere(['like', 'company', $this->company]);

        return $dataProvider;
    }
}
