<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Products;

/**
 * ProductSearch represents the model behind the search form of `app\models\Products`.
 */
class ProductSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'network_id', 'is_stock', 'is_active', 'is_deleted'], 'integer'],
            [['feed_id', 'name', 'currency', 'buy_url', 'description', 'advertiser_name','is_featured'], 'safe'],
            [['price', 'retail_price', 'sale_price'], 'number'],
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
        $query = Products::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['product_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product_id' => $this->product_id,
            'network_id' => $this->network_id,
            'price' => $this->price,
            'retail_price' => $this->retail_price,
            'sale_price' => $this->sale_price,
            'is_stock' => $this->is_stock,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'feed_id', $this->feed_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'buy_url', $this->buy_url])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'advertiser_name', $this->advertiser_name]);

        return $dataProvider;
    }
}
