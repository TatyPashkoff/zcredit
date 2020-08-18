<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Notify;

/**
 * NotifySearch represents the model behind the search form about `common\models\Notify`.
 */
class NotifySearch extends Notify
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'view_at', 'user_id', 'status', 'state', 'code'], 'integer'],
            [['msg'], 'safe'],
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
        $query = Notify::find();

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
            'created_at' => $this->created_at,
            'view_at' => $this->view_at,
            'user_id' => $this->user_id,
            'code' => $this->code,
            'status' => $this->status,
            'state' => $this->state,
        ]);

        $query->andFilterWhere(['like', 'msg', $this->msg]);

        return $dataProvider;
    }
}
