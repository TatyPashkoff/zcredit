<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Credits;

/**
 * CreditsSearch represents the model behind the search form about `common\models\Credits`.
 */
class CreditsSearch extends Credits
{

    public $clientFio;
    public $company;
    public $polis;
    public $asko;
    public $phone;
    public $priceName;
    public $supplierSum;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'user_id', 'supplier_id', 'credit_limit', 'quantity','credit_date','delivery_date','polis','asko','confirm','phone','service_type'], 'integer'],
            [['deposit_first', 'deposit_month', 'price'], 'number'],
            [['status','company','clientFio','priceName','supplierSum' ], 'safe'],
            [['itemsName',], 'string'],
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


        if(isset($params['user_id'])) {
            $query = Credits::find()->where(['user_id' => $params['user_id']]);
        }else{
            $query = Credits::find();
        }

        $query->joinWith(['creditItems']);
        $query->joinWith(['client']);
        $query->joinWith(['polis']);
        $query->joinWith(['asko']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);


        $dataProvider->sort->attributes['itemsName'] = [
            'asc' => [CreditItems::tableName().'.title' => SORT_ASC],
            'desc' => [CreditItems::tableName().'.title' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['supplierSum'] = [
            'asc' => [CreditItems::tableName().'.supplierSum' => SORT_ASC],
            'desc' => [CreditItems::tableName().'.supplierSum' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['clientFio'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC, User::tableName().'.lastname' => SORT_ASC, User::tableName().'.patronymic' => SORT_ASC ],
            'desc' => [User::tableName().'.username' => SORT_DESC, User::tableName().'.lastname' => SORT_DESC, User::tableName().'.patronymic' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['company'] = [
            'asc' => [User::tableName().'.company' => SORT_ASC],
            'desc' => [User::tableName().'.company' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phone'] = [
            'asc' => [User::tableName().'.phone' => SORT_ASC],
            'desc' => [User::tableName().'.phone' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['polis'] = [
            'asc' => [Polises::tableName().'.id' => SORT_ASC],
            'desc' => [Polises::tableName().'.id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['asko'] = [
            'asc' => [Polises::tableName().'.id' => SORT_ASC],
            'desc' => [Polises::tableName().'.id' => SORT_DESC],
        ];




        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            echo 'ddd';exit;
            return $dataProvider;
        }


        $query->andFilterWhere([
            Credits::tableName().'.id' => $this->id,
            Credits::tableName().'.status' => $this->status,
            Credits::tableName().'.user_confirm' => $this->confirm,
            Credits::tableName().'.created_at' => $this->created_at,
            Credits::tableName().'.user_id' => $this->user_id,
            Credits::tableName().'.supplier_id' => $this->supplier_id,
            Credits::tableName().'.credit_limit' => $this->credit_limit,
            Credits::tableName().'.deposit_first' => $this->deposit_first,
            Credits::tableName().'.deposit_month' => $this->deposit_month,
            'service_type' => $this->service_type,

        ]);

        $query->andFilterWhere(['like', CreditItems::tableName().'.title', $this->itemsName])
            ->andFilterWhere(['like', Credits::tableName().'.created_at', $this->itemsName])
            ->andFilterWhere(['like', User::tableName().'.username', $this->clientFio])
            ->andFilterWhere(['like', Polises::tableName().'.id', $this->polis])
            ->andFilterWhere(['like', Asko::tableName().'.id', $this->asko])
            ->andFilterWhere(['like', User::tableName().'.company', $this->company])
            ->andFilterWhere(['like', User::tableName().'.phone', $this->phone]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied delay
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchDelay($params)
    {

        if(isset($params['user_id'])) {
            $query = Credits::find()->where(['user_id' => $params['user_id']]);
        }else{
            $query = Credits::find();
        }

        if(!empty($params['CreditsSearch']['priceName'])) {
            if($params['CreditsSearch']['priceName'] == 1) {
                $credits = Credits::find()->All();
                foreach($credits as $credit){
                    if($credit->user_confirm == 1 ){
                        $sum = $credit->getPaymentDelaySum();
                        if($sum > 0){
                            $ids[] = $credit->id;
                        }
                    }
                }
                $query = Credits::find()->where(['in', Credits::tableName().'.id', $ids]);

            }else{
                $credits = Credits::find()->All();
                foreach($credits as $credit){
                    $sum = $credit->getPaymentDelaySum();
                    if($sum == 0){
                        $ids[] = $credit->id;
                    }
                }
                $query = Credits::find()->where(['in', Credits::tableName().'.id', $ids]);
            }

        }


        $query->joinWith(['client']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['priceName'] = [
            'asc' => ['priceName' => SORT_ASC],
            'desc' => ['priceName' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['clientFio'] = [
            'asc' => [User::tableName().'.username' => SORT_ASC, User::tableName().'.lastname' => SORT_ASC, User::tableName().'.patronymic' => SORT_ASC ],
            'desc' => [User::tableName().'.username' => SORT_DESC, User::tableName().'.lastname' => SORT_DESC, User::tableName().'.patronymic' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phone'] = [
            'asc' => [User::tableName().'.phone' => SORT_ASC],
            'desc' => [User::tableName().'.phone' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            echo 'ddd';exit;
            return $dataProvider;
        }


        $query->andFilterWhere([
            Credits::tableName().'.id' => $this->id,
            Credits::tableName().'.status' => $this->status,
            Credits::tableName().'.user_confirm' => $this->confirm,
            Credits::tableName().'.created_at' => $this->created_at,
            Credits::tableName().'.user_id' => $this->user_id,

        ]);

        $query->andFilterWhere(['like', Credits::tableName().'.created_at', $this->created_at])
            ->andFilterWhere(['like', User::tableName().'.username', $this->clientFio])
            ->andFilterWhere(['like', User::tableName().'.phone', $this->phone]);

        return $dataProvider;
    }

    public function searchItems($params)
    {
        $query = CreditItems::find()->where(['credit_id'=>$params['id']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
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
            'user_id' => $this->user_id,
            'supplier_id' => $this->supplier_id,
            'credit_limit' => $this->credit_limit,
            'deposit_first' => $this->deposit_first,
            'deposit_month' => $this->deposit_month,
            'price' => $this->price,
            Credits::tableName().'.confirm' => $this->confirm,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }

    public function searchBillingDetails($params,$id)
    {
        $query = CreditHistory::find()->where(['credit_id' => $contract['credit_id']])->orderBy('credit_date');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}
