<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Deals;

/**
 * DealSearch represents the model behind the search form of `app\models\Deals`.
 */
class DealSearch extends Deals {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['deal_id', 'is_active', 'is_deleted', 'coupon_id', 'program_id', 'featured', 'network_id'], 'integer'],
            [['title', 'content', 'coupon_code', 'voucher_types', 'start_date', 'end_date', 'expire_date', 'last_change_date', 'partnership_status', 'integration_code', 'customer_restriction', 'sys_user_ip', 'destination_url', 'discount_fixed', 'discount_variable', 'discount_code', 'network_id'], 'safe'],
            [['minimum_order_value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Deals::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['deal_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere(['>=', 'DATE(end_date)', date('Y-m-d')]);
        // grid filtering conditions
        $query->andFilterWhere([
            'deal_id' => $this->deal_id,
            'is_active' => $this->is_active,
            'is_deleted' => 0,
            'coupon_id' => $this->coupon_id,
            'program_id' => $this->program_id,
            'start_date' => $this->start_date,
            'expire_date' => $this->expire_date,
            'last_change_date' => $this->last_change_date,
            'featured' => $this->featured,
            'minimum_order_value' => $this->minimum_order_value,
            'network_id' => $this->network_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'coupon_code', $this->coupon_code])
                ->andFilterWhere(['like', 'voucher_types', $this->voucher_types])
                ->andFilterWhere(['like', 'partnership_status', $this->partnership_status])
                ->andFilterWhere(['like', 'integration_code', $this->integration_code])
                ->andFilterWhere(['like', 'customer_restriction', $this->customer_restriction])
                ->andFilterWhere(['like', 'sys_user_ip', $this->sys_user_ip])
                ->andFilterWhere(['like', 'destination_url', $this->destination_url])
                ->andFilterWhere(['like', 'discount_fixed', $this->discount_fixed])
                ->andFilterWhere(['like', 'discount_variable', $this->discount_variable])
                ->andFilterWhere(['like', 'discount_code', $this->discount_code]);

        //echo $query->createCommand()->rawSql;

        return $dataProvider;
    }

}
