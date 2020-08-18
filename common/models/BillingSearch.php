<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Billing;

/**
 * BillingHistorySearch represents the model behind the search form about `common\models\Billing`.
 */
class BillingSearch extends Billing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_id', 'created_at','payment_type', 'type', 'bil_his_id', 'bil_pay_id', 'status'], 'integer'],
            [['amount'], 'number'],
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
        $query = Billing::find();

        if(!empty($params['BillingSearch']['created_at'])) {
            $date = $params['BillingSearch']['created_at'];
            $begin = strtotime($date.'.07.2020 00:00:00');
            $end = strtotime($date.'.07.2020 23:59:59');
            $query = Billing::find()->where(['and',['<=',Billing::tableName().'.created_at',$end],['>=',Billing::tableName().'.created_at',$begin]]);
        }

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
            'client_id' => $this->client_id,
            'created_at' => $this->created_at,
            'amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
                ->andFilterWhere(['like', 'payment_type', $this->payment_type])
                 ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
