<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Alert;

/**
 * AlertSearch represents the model behind the search form of `common\models\Alert`.
 */
class AlertSearch extends Alert
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['group', 'type', 'message', 'start_dt', 'end_dt', 'created_dt'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Alert::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'start_dt' => $this->start_dt,
            'end_dt' => $this->end_dt,
            'created_dt' => $this->created_dt,
        ]);

        $query->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
