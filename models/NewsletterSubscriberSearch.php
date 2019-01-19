<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NewsletterSubscriber;

/**
 * NewsletterSubscriberSearch represents the model behind the search form of `app\models\NewsletterSubscriber`.
 */
class NewsletterSubscriberSearch extends NewsletterSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['newsletter_subscriber_id', 'is_active', 'is_deleted'], 'integer'],
            [['email', 'created_at'], 'safe'],
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
        $query = NewsletterSubscriber::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['newsletter_subscriber_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'newsletter_subscriber_id' => $this->newsletter_subscriber_id,
            'created_at' => $this->created_at,
            'is_active' => $this->is_active,
            'is_deleted' => 0,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
