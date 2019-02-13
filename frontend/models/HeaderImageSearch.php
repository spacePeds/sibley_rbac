<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\HeaderImage;

/**
 * HeaderImageSearch represents the model behind the search form of `frontend\models\HeaderImage`.
 */
class HeaderImageSearch extends HeaderImage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'paralex', 'brightness', 'offset', 'height', 'created_by'], 'integer'],
            [['image_path', 'image_idx', 'path', 'last_edit'], 'safe'],
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
        $query = HeaderImage::find();

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
            'paralex' => $this->paralex,
            'brightness' => $this->brightness,
            'offset' => $this->offset,
            'height' => $this->height,
            'last_edit' => $this->last_edit,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'image_path', $this->image_path])
            ->andFilterWhere(['like', 'image_idx', $this->image_idx])
            ->andFilterWhere(['like', 'path', $this->path]);

        return $dataProvider;
    }
}
