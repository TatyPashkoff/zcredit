<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CreditItems;

/**
 * CreditItemsSearch represents the model behind the search form about `common\models\CreditItems`.
 */
class CreditItemsSearch extends CreditItems
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'credit_id', 'quantity'], 'integer'],
            [['price', 'amount'], 'number'],
            [['title', 'article'], 'safe'],
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
        $query = CreditItems::find()->where(['credit_id'=>$params['credit_id']]);

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
            'price' => $this->price,
            'amount' => $this->amount,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'article', $this->article]);

        return $dataProvider;
    }
}
