<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Business;

/**
 * BusinessSearch represents the model behind the search form of `backend\models\Business`.
 */
class BusinessSearch extends Business
{
    public $nameUrl;
    public $fullAddress;
    public $contacts;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'address1', 'address2', 'city', 'state', 'zip', 'url', 'note', 'member', 'image', 'created_dt','nameUrl','fullAddress','contacts'], 'safe'],
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
        //Business->getContactMetods()
        if (Yii::$app->user->can('update_business')) {
            $query = Business::find()->with('contactMethods');
        } else {
            $query = Business::find()->leftJoin('contact_method', '`contact_method`.`business_id` = `business`.`id`')->where(['member'=> 1]);
        }
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         * Note: This is setup before the $this->load($params) 
         * statement below
         */
        $dataProvider->setSort([
            'attributes' => [
                'name',
                'nameUrl',
                'fullAddress' => [
                    'asc' => ['address1' => SORT_ASC, 'address2' => SORT_ASC],
                    'desc' => ['address1' => SORT_DESC, 'address2' => SORT_DESC],
                    'label' => 'Address',
                    'default' => SORT_ASC
                ],
                'city',
                'contacts' => [
                    'asc' => ['contact_method.description' => SORT_ASC],
                    'desc' => ['contact_method.description' => SORT_DESC],
                    'label' => 'Contacts',
                    'default' => SORT_ASC
                ],
            ]
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
            'created_dt' => $this->created_dt,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'zip', $this->zip])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'note', $this->note])
            //->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'contact_method.description', $this->contacts])
            ->andFilterWhere(['like', 'member', $this->member]);

            /* Setup your custom filtering criteria */

            // filter by person full name
            $query->andWhere('address1 LIKE "%' . $this->address1 . '%" ' .
            'OR address2 LIKE "%' . $this->address2 . '%" '.
            'OR CONCAT(address1, " ", address2) LIKE "%' . $this->fullAddress . '%"'
);

        return $dataProvider;
    }
}
