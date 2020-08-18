<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','role','supplier_id','nds_state','orentity','region_id', 'status', 'created_at','filial','cashback'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'auth_key','login','passport_main', 'passport_address', 'passport_serial','passport_date','passport_id','passport_issuer','nds','phone','company','filial','brand'], 'safe'],
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
        $query = User::find()->where(['role'=>1]); // показывать всех пользователей

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
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        $query->orderBy('role DESC');

        return $dataProvider;
    }

    public function searchClients($params)
    {
        $query = User::find()->where(['role'=>User::ROLE_CLIENT]); // показывать всех пользователей

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'cashback' => $this->cashback,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        $query->orderBy('role DESC');

        return $dataProvider;
    }

    public function searchSuppliers($params)
    {
        $query = User::find()->where(['role'=>User::ROLE_SUPPLIER]); // показывать всех вендоров

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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['like', 'filial', $this->filial])
            ->andFilterWhere(['like', 'supplier_id', $this->supplier_id])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }


    // поиск админов и менеджеров
    public function searchAdmins($params)
    {

        $query = User::find()->where(['role'=>[3,4,5,9]]);

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
            'status' => $this->status,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        return $dataProvider;
    }

}
