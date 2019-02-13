<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\SubPage;

/**
 * SubPageSearch represents the model behind the search form of `frontend\models\SubPage`.
 */
class SubPageSearch extends SubPage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'page_id', 'created_by'], 'integer'],
            [['title', 'body', 'type', 'path', 'last_edit'], 'safe'],
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
        $query = SubPage::find();

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
            'page_id' => $this->page_id,
            'last_edit' => $this->last_edit,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
