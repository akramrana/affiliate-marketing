<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Stores;

/**
 * StoreSearch represents the model behind the search form of `app\models\Stores`.
 */
class StoreSearch extends Stores
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'api_store_id', 'name', 'network_id', 'is_active', 'is_deleted'], 'integer'],
            [['store_url', 'description', 'store_logo'], 'safe'],
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
        $query = Stores::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['store_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'store_id' => $this->store_id,
            'api_store_id' => $this->api_store_id,
            'name' => $this->name,
            'network_id' => $this->network_id,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'store_url', $this->store_url])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'store_logo', $this->store_logo]);

        return $dataProvider;
    }
}
