<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CreditHistory;

/**
 * CreditHistorySearch represents the model behind the search form about `common\models\CreditHistory`.
 */
class CreditHistorySearch extends CreditHistory
{
    public $user_id;
    public $user_confirm;
    public $clientFio;
    public $phone;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'credit_id','credit_date','payment_date','delay','payment_type', 'payment_status','phone'], 'integer'],
            [['price'], 'number'],
            [['user_confirm','clientFio','user_id'], 'safe']
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
       
        if(isset($params['credit_id'])) {
            $query = CreditHistory::find()->where(['credit_id' => $params['credit_id']]);
        }else{
            $query = CreditHistory::find()->where(['<=', 'credit_date', time()])->andWhere(['payment_status' => '0'])->orderBy('credit_date');
        }

        //$query->joinWith(['client']);

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
            'payment_date' => $this->payment_date,
            'credit_date' => $this->credit_date,
            'delay' => $this->delay,
            'payment_type' => $this->payment_type,
            'payment_status' => $this->payment_status,
            'price' => $this->price,
        ]);


        $query->andFilterWhere(['like', Credits::tableName().'.user_id', $this->user_id]);
              //->andFilterWhere(['like', User::tableName().'.username', $this->clientFio]);


        return $dataProvider;
    }
}
