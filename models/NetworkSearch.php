<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Networks;

/**
 * NetworkSearch represents the model behind the search form of `app\models\Networks`.
 */
class NetworkSearch extends Networks
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['network_id', 'cron_daily', 'create_category', 'create_store', 'notify_stores', 'notify_categories', 'auto_publish', 'is_active', 'is_deleted'], 'integer'],
            [['network_name', 'network_customer_id', 'network_passphrase', 'network_site_id', 'network_site_locale'], 'safe'],
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
        $query = Networks::find()->where(['is_deleted' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['network_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'network_id' => $this->network_id,
            'cron_daily' => $this->cron_daily,
            'create_category' => $this->create_category,
            'create_store' => $this->create_store,
            'notify_stores' => $this->notify_stores,
            'notify_categories' => $this->notify_categories,
            'auto_publish' => $this->auto_publish,
            'is_active' => $this->is_active,
            'is_deleted' => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'network_name', $this->network_name])
            ->andFilterWhere(['like', 'network_customer_id', $this->network_customer_id])
            ->andFilterWhere(['like', 'network_passphrase', $this->network_passphrase])
            ->andFilterWhere(['like', 'network_site_id', $this->network_site_id])
            ->andFilterWhere(['like', 'network_site_locale', $this->network_site_locale]);

        return $dataProvider;
    }
}
