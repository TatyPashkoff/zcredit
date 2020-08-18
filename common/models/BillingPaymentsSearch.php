<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BillingPayments;

/**
 * BillingPaymentsSearch represents the model behind the search form about `common\models\BillingPayments`.
 */
class BillingPaymentsSearch extends BillingPayments
{
    public $fio;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'credit_item_id', 'credit_id', 'user_id', 'created_at','contract_id'], 'integer'],
            [['summ', 'debt'], 'number'],
            [['status','fio'], 'safe'],
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
        $query = BillingPayments::find()->with('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);
        /*$dataProvider->sort->attributes['fio'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC, User::tableName().'.lastname' => SORT_ASC, User::tableName().'.patronymic' => SORT_ASC ],
            'desc' => [User::tableName().'.username' => SORT_DESC, User::tableName().'.lastname' => SORT_DESC, User::tableName().'.patronymic' => SORT_DESC],
        ];*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'credit_item_id' => $this->credit_item_id,
            'credit_id' => $this->credit_id,
            'contract_id' => $this->credit_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            //'summ' => $this->summ,
            'debt' => $this->debt,
            'contract_id' => $this->contract_id,
            'status' => $this->status,
        ]);

        //$query->andFilterWhere(['like', 'fio', User::tableName().'.username', $this->fio]);
        $query->andFilterWhere(['like', 'summ', $this->summ]);

        return $dataProvider;
    }
}
